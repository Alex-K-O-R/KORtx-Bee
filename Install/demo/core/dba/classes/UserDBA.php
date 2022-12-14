<?php
namespace app\dba;

use app\Application;
use app\dba\constants\DBSettings;
use app\dba\inners\_SecurityDBA;
use app\dba\inners\_UserDBA;
use app\dba\inners\LogDBA;
use app\dba\constants\DBChanges;
use app\models;
use app\models\UserMDL;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class UserDBA extends _UserDBA{
    /** Создаёт базовый SQL запрос для получения всей информации о пользователях системы
     * @return array|bool|null|object
     */
    final function GLOBAL_getAllForAllSQL(){
        return 'SELECT '.self::fields_from(
            'user_id, sec_id, is_blocked, selected_lang, resource_hash, d_general_info, d_additional_info, d_name
            , d_surname, d_job, avatar_path, is_admin',
            self::table
        ).self::AND_.
            self::fields_from('login, activated, add_date, last_login_date',
                _SecurityDBA::table).'
        from '.self::table.' right JOIN '._SecurityDBA::table.' on '.self::table.'.sec_id = '._SecurityDBA::table.'.sec_id ';
    }



    /** Inverse SA rights for user
     * @param $uid
     * @param null $ModContext
     * @return null RETURNING is_admin
     */
    public function switchUserSuperAdminRights($uid, $ModContext = null){
        $uid = intval($uid);
        $row = $this->query(
            $this->UPDATE_RETURNING_TRICK_SQL(
                'UPDATE '.self::table.' set is_admin = case when is_admin = true then false else true end WHERE user_id = '.$uid.' '
                , 'is_admin', 'user_id', self::table
            )
            , 'row');
        if ($row && $row = $row['0']) {
            if ($ModContext == null) {
                LogDBA::logSystemAction($this, $uid, self::EntityCode(), !$row, $row, 'Installation', 'Changed SA rights for user with id ['.$uid.']', DBChanges::auto, DBChanges::level_high);
            } else {
                LogDBA::logUserAction($ModContext, !$row, $row, 'Changed SA rights for user with id ['.$uid.']', DBChanges::level_high);
            }
            return $row;
        } else return null;
    }


    /**
     * @param $UserId
     * @param $newVal
     * @param $lang_id
     * @param $ModCntxt
     */
    public function updateName($UserId, $newVal, $lang_id, $ModCntxt) {
        $this->GLOBAL_updateUserSimpleDynamics(
            $UserId,
            'd_name',
            'Name',
            $newVal, $lang_id, $ModCntxt
        );
    }

    /**
     * @param $UserId
     * @param $newVal
     * @param $lang_id
     * @param $ModCntxt
     */
    public function updateSurname($UserId, $newVal, $lang_id, $ModCntxt) {
        $this->GLOBAL_updateUserSimpleDynamics(
            $UserId,
            'd_surname',
            'Surname',
            $newVal, $lang_id, $ModCntxt
        );
    }

    /**
     * @param $UserId
     * @param $newVal
     * @param $lang_id
     * @param $ModCntxt
     */
    public function updateGeneralInfo($UserId, $newVal, $lang_id, $ModCntxt) {
        $this->GLOBAL_updateUserSimpleDynamics(
            $UserId,
            'd_general_info',
            'Name',
            $newVal, $lang_id, $ModCntxt
        );
    }

    /**
     * @param $UserId
     * @param $newVal
     * @param $lang_id
     * @param $ModCntxt
     */
    public function updateAdditionalInfo($UserId, $newVal, $lang_id, $ModCntxt) {
        $this->GLOBAL_updateUserSimpleDynamics(
            $UserId,
            'd_additional_info',
            'Detailed about info',
            $newVal, $lang_id, $ModCntxt
        );
    }

    /**
     * @param $UserId
     * @param $newVal
     * @param $lang_id
     * @param $ModCntxt
     */
    public function updateProfession($UserId, $newVal, $lang_id, $ModCntxt) {
        $this->GLOBAL_updateUserSimpleDynamics(
            $UserId,
            'd_job',
            'Main profession',
            $newVal, $lang_id, $ModCntxt
        );
    }


    /**
     * @param $uid
     * @param $login
     * @return array|null|string RETURNING user_id
     */
    public function createHashDirectoryForUser($uid, $login){
        $salt = DBSettings::dbprfx.'--'.$uid.'++'.$login;
        $hash = FileDBA::createHashForDirectory($salt, 10);
        $res = $this->query(
            $this->UPDATE_RETURNING_TRICK_SQL(
                'UPDATE '.self::table.' SET resource_hash =\''.$hash.'\' WHERE user_id = '.$uid.' '
                , 'user_id', null, self::table
            )
            , 'row');
        if(!$res && is_dir(FileDBA::DIR_PATH().$hash)){
            Application::LogTxt("Removing dir for user $uid", 'log.txt');
            if(mb_strlen($hash)>0){
                rmdir(FileDBA::DIR_PATH().$hash);
            }
        } else
            return $hash;
    }


    /**
     * @param UserMDL $UserMDL
     * @param $newFilePath
     * @param $ModCntxt
     */
    public function updateUserAvatarPath($UserMDL, $newFilePath, $ModCntxt){
        $newFilePath = CIE::l($newFilePath);
        if($newFilePath){
            $hash = CIE::l($UserMDL->getResourceHash());
            if(!$hash){
                $hash = $this->createHashDirectoryForUser($UserMDL->getUserId(), $UserMDL->getLogin());
            }

            //print $_SERVER["DOCUMENT_ROOT"]." :::\r\n ";
            copy($newFilePath, FileDBA::DIR_PATH().$hash.FileDBA::DIRECTORY_SEPARATOR.basename($newFilePath));
            //print FileDBA::DIR_PATH().$hash.FileDBA::DIRECTORY_SEPARATOR.basename($newFilePath)." Vss "."$newFilePath"; exit;

            $newFilePath = basename($newFilePath);
            $row = $this->historicalUpdate(self::table, 'avatar_path', $newFilePath, 'user_id', $UserMDL->getUserId(), null);

            if($row && $row[0] != $newFilePath){
                //print $row[0]." Vs "."$newFilePath"; exit;
                FileDBA::removeFile(FileDBA::DIR_PATH($hash).$row[0]);
            }
            LogDBA::logUserAction($ModCntxt, $row[0], $newFilePath, 'Uploaded new photo for user: '.$UserMDL->getUserId());
        }
    }


    /**
     * @param $userId
     * @param $ModificationContext
     * @param bool $delete_resource_directory
     * @return bool
     */
    public function deleteUserById($userId, $ModificationContext, $delete_resource_directory = true) {
        $entityFetch = $this->getUsersInfoesByUsersIds(1,0,null, $userId);
        if($entityFetch)
            $entityFetch = $entityFetch[0];
        else return false;

        $dynamicIdsToDelete = array_intersect_key($entityFetch, array_flip(UserMDL::getDynamicFieldsIndexes()));
        $this->deleteDynamicStringsForAllLanguagesByIds($dynamicIdsToDelete, self::EntityCode());

        $UserMDL = new UserMDL($entityFetch);
        $this->deleteSecuRecById($UserMDL->getSecId(), $ModificationContext);

        if($delete_resource_directory) FileDBA::removeDirectory(FileDBA::DIR_PATH($UserMDL->getResourceHash()));

        if ($this->query('DELETE from '.self::table.' WHERE user_id = \''.$UserMDL->getUserId().'\';')) {
            LogDBA::logUserAction($ModificationContext, $UserMDL->getUserId(), null, 'User was removed: '.$UserMDL->getLogin(), DBChanges::level_medium);
            return true;
        } else return false;
    }

}