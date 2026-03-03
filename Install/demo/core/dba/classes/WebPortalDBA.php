<?php
namespace app\dba;

class WebPortalDBA extends inners\WebPortalDBA{

    public function getFavoriteUsersIdsForUser($uid){
        return $this->query(
            WebPortalDBA::getFavoritesForEntitySQL(UserDBA::EntityCode(), $uid, UserDBA::EntityCode())
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
            WebPortalDBA::removeFavoriteOrBannedOrDislikedRelationsForEntitySQL(UserDBA::EntityCode(), $uid, UserDBA::EntityCode(), $fav_uid)
            , 'row');
        if($row && $row[0]) return false;
        return null;
    }




}