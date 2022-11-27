<?php
namespace app\dba;

use app\dba\constants\DBSettings;
use app\dba\inners\_SecurityDBA;
use app\dba\inners\_UserDBA;
use app\dba\inners\LogDBA;
use app\dba\constants\DBChanges;
use app\dba\inners\_WebPortalDBA;
use app\models;
use app\models\UserMDL;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class WebPortalDBA extends _WebPortalDBA{

    public function getFavoriteUsersIdsForUser($uid){
        return $this->query(
            _WebPortalDBA::getFavoritesForEntitySQL(UserDBA::EntityCode(), $uid, UserDBA::EntityCode())
            , 'arr');
    }


    /** Добавляет запись об избранном пользователе; true, если выполнено (для удобства вывода); null если не удалось
     * @param $uid
     * @param $fav_uid
     * @return bool|null
     */
    public function addFavoriteUserForUser($uid, $fav_uid){
        $row = $this->query(
            $this->addFavoriteOrBannedOrDislikedRelationsForEntitySQL(UserDBA::EntityCode(), $uid, UserDBA::EntityCode(), $fav_uid)
            , 'row');
        if($row && $row[0]) return true;
        return null;
    }

    /** Удаляет запись об избранном пользователе; false, если выполнено (для удобства вывода); null если не удалось
     * @param $uid
     * @param $fav_uid
     * @return bool|null
     */
    public function removeFavoriteUserForUser($uid, $fav_uid){
        $row = $this->query(
            _WebPortalDBA::removeFavoriteOrBannedOrDislikedRelationsForEntitySQL(UserDBA::EntityCode(), $uid, UserDBA::EntityCode(), $fav_uid)
            , 'row');
        if($row && $row[0]) return false;
        return null;
    }




}