<?php
namespace app\dba;
use app\Application;
use app\Core;
use app\dba\constants\DBChanges;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class DBAccess extends DBAccessGeneric {
    /** These monsters should be removed in time, when ... All SQLs will have much more in common, and RETURNINGs will become available anywhere. Like in PostgreSQL!!! Thumbs up, PG!
     * @param $base_sql
     * @param $returningCol
     * @param null $keyField
     * @param string $table
     * @return mixed
     */
    protected function UPDATE_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table)
    {
        $base_sql .= ' RETURNING '.$returningCol;
        return $base_sql;
    }

    protected function INSERT_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table)
    {
        $base_sql .= ' RETURNING '.$returningCol;
        return $base_sql;
    }

    public function escape_string($string)
    {
        return pg_escape_string($string);
    }
    /**
     * @param $table
     * @param $field
     * @param $value
     * @param $keyField
     * @param $keyVal
     * @param $lang_id
     * @return null ' RETURNING y.'.$field.''
     */
    final protected function historicalUpdate($table, $field, $value, $keyField, $keyVal, $lang_id){
        if(CIS::l($keyVal)===null||!CIS::l($keyField)===null||!CIS::l($lang_id)===null && $keyField==='string_id') return null;
        $value = $this->escape_string($value);
        return $this->query(
            $this->UPDATE_RETURNING_TRICK_SQL(
                'UPDATE '.$table.' x
                    SET '.$field.' = \''.$value.'\'
                    FROM  (SELECT '.$field.', '.$keyField.'  FROM '.$table.' WHERE '.$keyField.' = \''.$keyVal.'\' order by '.$field.' desc limit 1 FOR UPDATE) y
                    WHERE  x.'.$keyField.' = y.'.$keyField.' '.($lang_id === null?'':' AND lang_id = \''.$lang_id.'\'')
                , 'y.'.$field, $keyField, $table
            )
            , 'row');
        /** Alternatives w/o block:
        UPDATE '.$table.'
        SET avatar_path = 'Avatar_g3.png'
        FROM '.$table.' AS old_'.$table.'
        WHERE old_'.$table.'.user_id = (
        SELECT user_id
        FROM '.$table.'
        WHERE user_id = 2
        LIMIT 1
        )
        AND '.$table.'.user_id = old_'.$table.'.user_id
        RETURNING '.$table.'.user_id, '.$table.'.avatar_path, old_'.$table.'.avatar_path AS old_processing_by

        UPDATE '.$table.' SET avatar_path = 'Avatar_g6.png' WHERE user_id = 2
        RETURNING (SELECT avatar_path FROM '.$table.' WHERE user_id = 2);


        WITH sel AS (
        SELECT avatar_path, user_id FROM '.$table.' WHERE user_id = 2
        )
        , upd AS (
        UPDATE '.$table.' SET avatar_path= 'New Guy' WHERE user_id = 2
        RETURNING avatar_path, user_id
        )
        SELECT s.user_id AS old_id, s.avatar_path As old_name
        , u.user_id , u.avatar_path
        FROM   sel s, upd u;
         */
    }


    /**
     * @param $ent_type
     * @param $lang_id
     * @param string $value
     * @param null $string_id
     * @return null RETURNING string_id
     */
    final protected function addDynamicString($ent_type, $lang_id, $value = '', $string_id = null){
        if(!$ent_type) return null;
        $lang_id = intval($lang_id);
        $value = $this->escape_string($value);
        if($string_id===null || $string_id ===''){
            $query = $this->query(
                $this->INSERT_RETURNING_TRICK_SQL(
                    'INSERT INTO '.DBAccess::table.' (string_id, lang_id, value, ent_type)
                        WITH existing AS ('.
                        self::GLOBAL_getFirstEmptyIndexFromNonAutoIncrementTableSQL('string_id', self::table).
                        ')
                    SELECT string_id, '.$lang_id.', \''.CIE::l($value).'\', \''.$ent_type.'\' FROM existing'
                    , 'string_id')
                , 'row'
            );
        } else {
            $query = $this->query(
                $this->INSERT_RETURNING_TRICK_SQL('INSERT INTO '.self::table.' (string_id, lang_id, value, ent_type)
                VALUES('.intval($string_id).', '.$lang_id.', \''.CIE::l($value).'\', \''.$ent_type.'\')
                ', 'string_id')
                , 'row');
        }

        if ($query) {
            return $query[0];
        } else return null;
    }


    final function query($sql, $fetch = null, Page $page = null) {
        if (is_null($page)) {
            $pgResult = pg_query($sql);
        } else {
            $pgResult = pg_query($this->wrapSQL($sql, $page));
        }

        if(Application::DEBUG_MODE && pg_last_error()){
            Application::LogTxt("\r\n\r\n\r\n".date('d-m-Y H:i:s').": Ошибка SQL [".pg_last_error()."]:\r\n".is_null($page)?$sql:$this->wrapSQL($sql, $page), "my-sql-errors.log");
        }

        if (!pg_last_error() && $pgResult){
            if (is_null($fetch)) {
                return pg_fetch_all($pgResult);
            } else {
                switch($fetch){
                    case 'row':
                        return pg_fetch_row($pgResult);
                        break;
                    case 'arr': //DONE! TODO : Perhaps will be cool if mixed with *set* method when single collumn appears..
                        $list = array();
                        $SCR = null; //Single Column Result
                        //print_r($rw = pg_fetch_array($pgResult, NULL, PGSQL_NUM));
                        //Core::__500Report('----------------------Step by step------------------------------');
                        while ($row = pg_fetch_array($pgResult, NULL, PGSQL_NUM)) {
                            $list[] = $row;
                            //Core::__500Report('> '.print_r($row, true));
                            if($SCR === null){
                                //Core::__500Report('>> '.print_r(count($row), true));
                                if(count($row)<2){return pg_fetch_all_columns($pgResult);}else{$SCR = false;}
                            }
                        }
                        //Core::__500Report("\r\n-\r\n-\r\n-");
                        return $list;
                        break;
                    case 'aff':
                        return pg_affected_rows($pgResult);
                        break;
                    case 'asc':
                        return pg_fetch_all($pgResult);
                        break;
                    /*case 'set':
                        return pg_fetch_all_columns($pgResult);
                        break;*/
                }
            }
        }

        return null;
    }

}

