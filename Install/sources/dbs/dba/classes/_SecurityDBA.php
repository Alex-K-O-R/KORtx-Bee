<?php

namespace app\dba\inners;

use app\dba\IModelDataProvider;
use app\dba\constants\DBSettings;
use app\dba\Page;
use app\dba\constants\DBChanges;
use app\dba\DBAccess;
use app\models\inner\_UserMDL;
use app\dba\inners\LogDBA;
use app\utilities\inner\CIE;


class _SecurityDBA extends DBAccess implements IModelDataProvider {
    const activation_hash_formula = 'md5(login ||\'---\' || sec_id)';
    const table = DBSettings::dbprfx.'_security';
    static function EntityCode($codeLength = 3){return 'sbo';}


    /** Создаёт базовый SQL запрос для получения всей информации о пользователях системы
     * @return array|bool|null|object
     */
    public function GLOBAL_getAllForAllSQL(){
        return 'SELECT '.
            self::fields_from('sec_id, login, activated, add_date, last_login_date',
                self::table).'
        from '.self::table.' ';
    }


    /**
     * @param $login
     * @param $password
     * @param $ModificationContext
     * @return bool RETURNING sec_id
     */
    public function addInitialSecurityObject($login, $password, $ModificationContext) {
        if ($login && $password && $ModificationContext) {
            if($this->checkIfLoginExists($login)) return false;
            $password = trim($password);
            $password = $this->escape_string($password);
            $row = $this->query(
                $this->INSERT_RETURNING_TRICK_SQL(
                    'INSERT INTO '.self::table.' (login, pass)
                    VALUES (\''.$login.'\', \''.md5($password).'\') ', 'sec_id'
                    , null, self::table
                ), 'row');

            if($row && $row = $row[0]){
                LogDBA::logUserAction($ModificationContext, null, $row, 'New security record is added with sec_id '.$row, DBChanges::level_medium);
                return $row;
            }
        } else return false;
    }


    /**
     * @param $sid
     * @param null $ModificationContext
     * @return null RETURNING login
     */
    public function activateSecurityAcc($sid, $ModificationContext = null){
        $sid = intval($sid);
        $row = $this->query(
            $this->UPDATE_RETURNING_TRICK_SQL(
                'UPDATE '.self::table.' set activated = true WHERE sec_id ='.$sid.' '
                , 'login', 'sec_id', self::table
            )
            , 'row');
        if ($row && $row = $row['0']) {
            if($ModificationContext)
                LogDBA::logUserAction($ModificationContext,'', true, 'Account ['.$row.'] was activated', DBChanges::level_medium);
            else
                LogDBA::logSystemAction($this, $sid, self::EntityCode(), '', true, -1, 'Account ['.$row.'] was activated', DBChanges::auto, DBChanges::level_medium);
            return $row;
        } else return null;
    }



    /** Проверяет присутствие в базе логина, чтобы избежать дубликатов
     * @param $login
     * @return array|null|object
     */
    public function checkIfLoginExists($login){
        $login = CIE::l($login);

        if($login){
            $login = $this->escape_string($login);
            return $this->query(
                self::GLOBAL_getAllForAllSQL().
                    'WHERE login = \'' . $login . '\'', 'obj');
        }
    }


    /** Аутентификация
     * @param $login
     * @param $password
     * @return mixed
     */
    public function checkAuth($login, $password) {
        $login = trim($login);
        $login = $this->escape_string($login);
        $password = trim($password);
        $password = md5($this->escape_string($password));
        if ((!empty($login)) and (!empty($password))) {
            $result = $this->query('SELECT sec_id FROM '.self::table.' WHERE
            pass = \''.$password.'\' AND login = \''.$login.'\'', 'row');
            return ($result && CIE::l($result, 0))?true:false;
        }
    }


    public function refreshLastLoginDate($sid) {
        $sid = intval($sid);
        $this->query('UPDATE '.self::table.' SET
        last_login_date = NOW() WHERE sec_id = \''.$sid.'\'');
    }















    /** Возвращает информацию о пользователе $uid
     * @param $uid
     * @return _UserMDL
     */
    public function getSecuRecInfoBySecuRecId($sid) {
        $sid = intval($sid);
        return $this->query(
            self::GLOBAL_getAllForAllSQL().
                'WHERE '._UserDBA::table.'.sec_id = '.$sid, 'row'
        );
    }


    /** Deletes security record from db making login unusable
     * @param $sec_id
     * @param $ModificationContext
     * @return bool
     */
    protected function deleteSecuRecById($sec_id, $ModificationContext) {
        if ($sec_id && $ModificationContext) {
            $sec_id = intval($sec_id);

            if ($row = $this->query('DELETE from '.self::table.' WHERE sec_id = \''.$sec_id.'\' RETURNING login', 'row')) {
                LogDBA::logUserAction($ModificationContext, $sec_id, null, 'Security acc was deleted; '.$row[0], DBChanges::level_critical);
                return true;
            } else return false;
        } else return false;
    }
}
