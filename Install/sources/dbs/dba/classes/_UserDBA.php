<?php

namespace app\dba\inners;

use app\dba\IModelDataProvider;
use app\dba\constants\DBSettings;
use app\dba\Page;
use app\dba\constants\DBChanges;
use app\dba\DBAccess;
use app\dba\inners\LogDBA;
use app\models;
use app\utilities\inner\Array_;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class _UserDBA extends _SecurityDBA implements IModelDataProvider {
    const table = DBSettings::dbprfx.'_e_user_info';
    static function EntityCode($codeLength = 3){return 'usr';}


    /** Создаёт базовый SQL запрос для получения всей информации о пользователях системы
     * @return array|bool|null|object
     */
    public function GLOBAL_getAllForAllSQL(){
        return 'SELECT '.self::fields_from(
            'user_id, sec_id, is_blocked, selected_lang',
            self::table
        ).self::AND_.
            self::fields_from('login, activated, add_date, last_login_date',
                _SecurityDBA::table).'
        from '._UserDBA::table.' right JOIN '._SecurityDBA::table.' on '._UserDBA::table.'.sec_id = '._SecurityDBA::table.'.sec_id ';
    }


    /** Добавляет новую учётную запись
     * @param $login
     * @param $password
     * @param $ModificationContext
     * @return bool RETURNING user_id
     */
    public function addUser($login, $password, $ModificationContext) {
        if ($login && $password && $ModificationContext) {
            $secId = $this->addInitialSecurityObject($login, $password, $ModificationContext);
            if($secId){
                $row = $this->query(
                    $this->INSERT_RETURNING_TRICK_SQL(
                        'INSERT INTO '.self::table.' (sec_id)
                        VALUES (\''.$secId.'\') ' ,'user_id', null, self::table
                    )
                    , 'row');

                if($row && $row = $row[0]){
                    LogDBA::logUserAction($ModificationContext, null, $row, 'New user is added with id '.$row, DBChanges::level_medium);
                    return $row;
                }
            } else {
                LogDBA::logUserAction($ModificationContext, null, $secId, 'Failed to create new user with login '.$login, DBChanges::level_high);
                return false;
            }
        } else return false;
    }

//This crazy method is used to update dialect-depending values. It is used in extending classes. Better to look there then here.
    protected function GLOBAL_updateUserSimpleDynamics($uid, $DynStrLinkColumn, $DynStrLinkColumnLogName, $newValue, $lang_id, $ModCntxt) {
        if ($ModCntxt) {
            $uid = intval($uid);
            $DynStrIndex = $this->query('SELECT '.$DynStrLinkColumn.' FROM '.self::table.'
                                        WHERE user_id = '.$uid.' LIMIT 1', 'row');
            $DynStrIndex = $DynStrIndex ? $DynStrIndex[0] : null;
            $newValue = $this->escape_string($newValue);
            if(CIS::l($newValue,null,null)){
                $prevVal = null;
                if(CIE::l($DynStrIndex) && $prevVal = self::getStringDynamicsByStringId($lang_id, $DynStrIndex, $uid)) {
                    if($prevVal && $prevVal[0][2]!=$newValue){
                        self::updateDynamicStringById($DynStrIndex, $lang_id, $newValue);
                        LogDBA::logUserAction($ModCntxt, $prevVal[0][2], $newValue, 'Property was updated ['.$DynStrLinkColumnLogName.'] for user', DBChanges::level_low);
                    }
                } else {
                    $newStrKey = self::addDynamicString(self::EntityCode(), $lang_id, $newValue, $DynStrIndex);
                    $this->query('UPDATE '.self::table.' SET '.$DynStrLinkColumn.' =\''.$newStrKey.'\' WHERE user_id = '.$uid);
                    LogDBA::logUserAction($ModCntxt, '', $newValue, 'Property description created ['.$DynStrLinkColumnLogName.'] for user and language ('.$lang_id.')', DBChanges::level_low);
                }
            } else {
                if($DynStrIndex){
                    $was = self::deleteDynamicStringById($DynStrIndex, $lang_id);
                    $this->query('UPDATE '.self::table.' SET '.$DynStrLinkColumn.' =
                                (CASE WHEN EXISTS(SELECT string_id from new_one_strings WHERE string_id = '.$DynStrLinkColumn.' LIMIT 1) then '.$DynStrLinkColumn.' else NULL end)
                                WHERE user_id = '.$uid);
                    LogDBA::logUserAction($ModCntxt, $was, $newValue, 'Property was deleted ['.$DynStrLinkColumnLogName.'] for user', DBChanges::level_low);
                }
            }
        } else {return null;}
    }



    /**
     * @param $login
     * @return array
     */
    public function getUserInfoByLogin($login) {
        $login = CIE::l($login);

        if($login){
            $login = $this->escape_string($login);
            return $this->query(
                $this->GLOBAL_getAllForAllSQL().
                    'WHERE '._SecurityDBA::table.'.login = \''.$login.'\'', 'row'
            );
        }
    }


    /**
     * @param int $pageSize
     * @param int $pageNum
     * @param null $Filter
     * @param null $uids
     * @return array|bool|int|null|object
     */
    public function getUsersInfoesByUsersIds($pageSize=10, $pageNum=0, $Filter=null, $uids=null) {
        $pageNum = intval($pageNum);
        $pageSize = intval($pageSize);

        if($uids && !is_string($uids)){
            $uids = Array_::varToArray($uids);
            $uids = array_map(function($a){return intval($a);}, $uids);
        }
        $PageObject = new Page('last_login_date', $pageNum, $pageSize, 'DESC');
        $query = $this->GLOBAL_getAllForAllSQL().
            ' WHERE 1=1 '.
            (CIE::l($uids)?(' AND '.self::table.'.user_id IN (' .(is_string($uids) ? $uids : implode(',', $uids)) . ')'):'')
            .(($Filter===null)?'':$Filter->getResultSQLConditions());

        $res = $this->query($query, 'arr', $PageObject);
        if (!empty($res)) return $res; else return null;
    }


    /**
     * @param null $Filter
     * @param null $uids
     * @return array|int|null|object
     */
    public function getCountTopUserSQL($Filter=null, $uids=null){
        if($uids && !is_string($uids)){
            $uids = Array_::varToArray($uids);
            $uids = array_map(function($a){return intval($a);}, $uids);
        }
        $query = $this->GLOBAL_getAllForAllSQL().
            ' WHERE 1=1 '.
            (CIE::l($uids)?(' AND '.self::table.'.user_id IN (' .(is_string($uids) ? $uids : implode(',', $uids)) . ')'):'')
            .(($Filter===null)?'':$Filter->getResultSQLConditions());

        $res = $this->query($query, 'aff');
        if (!empty($res)) return $res; else return 0;
    }


    /**
     * @param $uid
     * @param $acronym
     * @return null RETURNING selected_lang
     */
    public function updateUserPreferredLanguage($uid, $acronym){
        $uid = intval($uid);
        $acronym = CIE::l($acronym);
        if($acronym) {
            $acronym = $this->escape_string($acronym);
            $row = $this->query(
                $this->UPDATE_RETURNING_TRICK_SQL(
                    'UPDATE '.self::table.' set selected_lang = \''.$acronym.'\' WHERE user_id ='.$uid
                    , 'selected_lang', 'user_id', self::table
                )
                , 'row');
            if ($row && $row = $row[0]) {
                return $row;
            } else return null;
        }
    }

    /**
     * @param $userId
     * @param $newBlockState
     * @param $ModificationContext
     * @return null
     */
    public function setUserBlock($userId, $newBlockState, $ModificationContext){
        if(!(CIE::l($userId) || is_bool($newBlockState) || $ModificationContext)) return null;
        $newBlockState = $newBlockState===false ? 0 : 1;
        $userId = intval($userId);
        $row = $this->historicalUpdate(self::table, 'is_blocked', $newBlockState, 'user_id', $userId, null);
        if($row && $row[0]){
            LogDBA::logUserAction($ModificationContext, $row, $newBlockState, "User block was changed for id ".$userId, DBChanges::level_high);
            return $newBlockState;
        }
    }
}
