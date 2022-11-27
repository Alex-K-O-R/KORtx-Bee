<?php
namespace app\dba\inners;

use app\dba\constants\DBSettings;
use app\dba\DBAccess;
use app\dba\DBModificationContext;
use app\dba\constants\DBChanges;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;
use app\Application;

class _WebPortalDBA extends DBAccess {
    const table_languages = DBSettings::dbprfx.'_languages';
    const table_geo = DBSettings::dbprfx.'_geography_common';
    const table_last_visits = DBSettings::dbprfx.'_last_seen';
    const table_favorites = DBSettings::dbprfx.'_favorites';

    /** Получает информацию о языках, используемых в системе
     * @param $lang_acr
     * @return array|null
     */
    public function getLanguageInfo($lang_id = null){
        return $this->query('SELECT lang_id, acronym, fullname, php_datemask, js_datemask from ' . self::table_languages .
            strval(($lang_id==null)?'':(' WHERE lang_id = \'' . $lang_id . '\'')), 'arr');
    }



    public function updateLastSeenForEntity($uid, $ent_type, $ent_id){
        $uid = intval($uid);
        $ent_type = CIS::l($ent_type);
        $ent_id = intval($ent_id);

        $cand = $this->getLastSeenForEntityUpdateCandidateDatetime($uid, $ent_type, $ent_id);

        if($cand){
            $this->query('UPDATE '.self::table_last_visits.'
                            SET subject_id = '.$ent_id.'
                            , view_date = DEFAULT
                            WHERE user_id = '.$uid.' AND subject_type = \''.$ent_type.'\'
                            AND view_date = \''.$cand.'\'');
        } else {
            $this->query('INSERT INTO '.self::table_last_visits.'
                        (user_id, subject_id, subject_type, view_date)
                            VALUES('.$uid.', '.$ent_id.', \''.$ent_type.'\', DEFAULT)');
        }
    }

    /**
     * @param $uid
     * @param $ent_type
     * @return string
     */
    public function getLastVisitedIdsForUserAndEntitySQL($uid, $ent_type){
        return
            'SELECT DISTINCT(subject_id)
                                FROM '.self::table_last_visits.' WHERE user_id = '.$uid.'
            AND subject_type = \''.$ent_type.'\'';
    }





    public function getFavoritesForEntitySQL($ent_type, $ent_id, $fav_type){
        $ent_id = intval($ent_id);
        $ent_type = CIS::l($ent_type);
        $fav_type = CIS::l($fav_type);
        return 'SELECT fav_id FROM '.self::table_favorites.'
                                WHERE ent_id = '.$ent_id.' AND ent_type = \''.$ent_type.'\'
                                AND fav_type = \''.$fav_type.'\' AND inverse = false';
    }
    /** Возвращает SQL для получения всех айдишников неполюбившихся пользователю экземпляров
     * @param $ent_type
     * @param $ent_id
     * @param $fav_type
     * @return string
     */
    public static function getBannedOrDislikedForEntitySQL($ent_type, $ent_id, $fav_type){
        $ent_id = intval($ent_id);
        $ent_type = CIS::l($ent_type);
        $fav_type = CIS::l($fav_type);
        return 'SELECT fav_id FROM '.self::table_favorites.'
                                WHERE ent_id = '.$ent_id.' AND ent_type = \''.$ent_type.'\'
                                AND fav_type = \''.$fav_type.'\' AND inverse = true';
    }






    /** Возвращает SQL для удаления записи об отношениях пользователя и экземпляров (тоже sql, т.к. пусть метод на удаление не будет общим)
     * @param $ent_id
     * @param $ent_type
     * @param $fav_id
     * @param $fav_type
     * @return string
     */
    public static function removeFavoriteOrBannedOrDislikedRelationsForEntitySQL($ent_type, $ent_id, $fav_type, $fav_id){
        $ent_id = intval($ent_id);
        $ent_type = CIS::l($ent_type);
        $fav_id = intval($fav_id);
        $fav_type = CIS::l($fav_type);

        return 'DELETE FROM '.self::table_favorites.'
                WHERE ent_id = '.$ent_id.' AND ent_type = \''.$ent_type.'\'
                AND fav_id = '.$fav_id.' AND fav_type = \''.$fav_type.'\'
                RETURNING fav_id';
    }


    /** Возвращает SQL для записи отношения пользователя и экземпляров
     * @param $ent_id
     * @param $ent_type
     * @param $fav_id
     * @param $fav_type
     * @param bool $dislike
     * @return string
     */
    public static function addFavoriteOrBannedOrDislikedRelationsForEntitySQL($ent_type, $ent_id, $fav_type, $fav_id, $dislike = false){
        $ent_id = intval($ent_id);
        $ent_type = CIS::l($ent_type);
        $fav_id = intval($fav_id);
        $fav_type = CIS::l($fav_type);

        return self::removeFavoriteOrBannedOrDislikedRelationsForEntitySQL($ent_type, $ent_id, $fav_type, $fav_id).'; INSERT INTO '.self::table_favorites.'
                        (ent_type, ent_id, fav_type, fav_id, inverse)
                            VALUES(\''.$ent_type.'\', '.$ent_id.', \''.$fav_type.'\', '.$fav_id.', '.($dislike?'true':'DEFAULT').');
                            SELECT '.$fav_id.' FROM '.self::table_favorites.' WHERE ent_id = '.$ent_id.' AND ent_type = \''.$ent_type.'\'
                                AND fav_type = \''.$fav_type.'\';';
    }





















    private function getLastSeenForEntityUpdateCandidateDatetime($uid, $ent_type, $ent_id){
        $last_seen_depth = 6;
///*AND subject_id = '.$ent_id.'*/
        $row = $this->query('WITH curr_amnt as (
                                (SELECT COUNT(*) as t, MIN(view_date) s
                                , MAX(CASE WHEN subject_id = '.$ent_id.' then view_date else null end) prev
                                FROM '.self::table_last_visits.' WHERE user_id = '.$uid.'
                                AND subject_type = \''.$ent_type.'\')
                                )
                                select
                                case
                                when
                                prev is not null
                                then
                                prev
                                when
                                curr_amnt.t > '.$last_seen_depth.'
                                then
                                curr_amnt.s
                                else
                                null
                                end
                                from curr_amnt', 'row');
        if($row){
            return $row['0'];
        } else {
            return null;
        }
    }


}
