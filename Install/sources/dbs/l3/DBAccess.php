<?php
namespace app\dba;
use SQLite3;
use app\Application;
use app\Core;
use app\dba\constants\DBChanges;
use app\dba\constants\DBSettings;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class DBAccess extends DBAccessGeneric {
    private $SQLITE_OK_CODES_ARR = array(
        0 //SQLITE_OK
    , 100 //SQLITE_ROW
    , 101 //SQLITE_DONE
    );

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

    /** This function participates in multistatements queries. Be aware.
     * @param $string
     * @return string
     */
    public function escape_string($string)
    {
        $string = str_replace(';', '\;', $string);
        return SQLite3::escapeString($string);
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
        /* Lock producing attempt:
        $sql = 'BEGIN;
                PRAGMA temp_store = 2; -- 2 means use in-memory
                CREATE TEMP TABLE IF NOT EXISTS _Variables (Name TEXT PRIMARY KEY, Value TEXT);
                INSERT OR REPLACE INTO _Variables VALUES ("old", (SELECT '.$field.' from '.$table.' WHERE '.$keyField.' = "'.$keyVal.'" limit 1));

                UPDATE '.$table.' SET '.$field.' = "'.$value.'"
                   WHERE '.$keyField.' = "'.$keyVal.'";

                SELECT Value
                  FROM _Variables
                 WHERE Name = "old";

                DROP TABLE _Variables;
                END;';*/
        $old = $this->query('SELECT '.$field.' from '.$table.' WHERE '.$keyField.' = "'.$keyVal.'" limit 1', 'row');
        if($old && $old[0]) $old = $old[0];

        $new = $this->query(
            'UPDATE '.$table.' SET '.$field.' = "'.$value.'"
                   WHERE '.$keyField.' = "'.$keyVal.'";', 'row');
        if($new && $new[0]){
            return $old;
        }
        return null;
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
        //([A-Za-zА-Яа-я0-9])+\.
        /**
        Не все русские плохие.
         */
        $resSqls = array();
        $sql = mb_convert_encoding($sql, 'UTF-8');
        $tmp = preg_split("/(?<!\\\\)(;[\\r\\n]+)/u", $sql, -1, PREG_SPLIT_NO_EMPTY);
        foreach($tmp as $s){
            $str = str_replace('\;',';',$s);
            $resSqls[] = $str;
        }
        //print_r($resSqls);
        //$db = $this->conn_provider->get();

        $Result = null;

        if($fetch!=='aff'){
            if (is_null($page)) {
                foreach($resSqls as $s){
                    $myResult = $this->conn_provider->get()->query($s);
                }
            } else {
                foreach($resSqls as $s){
                    $myResult = $this->conn_provider->get()->query($this->wrapSQL($s, $page));
                }
            }

            if(Application::DEBUG_MODE && !in_array($this->conn_provider->get()->lastErrorCode(), $this->SQLITE_OK_CODES_ARR)){
                Application::LogTxt("\r\n\r\n\r\n".date('d-m-Y H:i:s').": Ошибка SQL #(".$this->conn_provider->get()->lastErrorCode().") [".$this->conn_provider->get()->lastErrorMsg()."]:\r\n".(is_null($page)?$sql:$this->wrapSQL($sql, $page)), "my-sql-errors.log");
            }
            if (is_bool($myResult)) {$Result = $myResult;} else {
                if (in_array($this->conn_provider->get()->lastErrorCode(), $this->SQLITE_OK_CODES_ARR) && $myResult->numColumns()){
                    if (is_null($fetch)) {
                        while ($Result = $myResult->fetchArray()){}
                    } else {
                        switch($fetch){
                            case 'row':
                                while ($row = $myResult->fetchArray(SQLITE3_NUM)) {
                                    if($row) $Result = $row;
                                }
                                break;
                            case 'arr': //DONE! TODO : Perhaps will be cool if mixed with *set* method when single collumn appears..
                                $list = array();
                                $SCR = ($myResult->numColumns()===1)?true:false; //Single Column Result
                                while ($row = $myResult->fetchArray(SQLITE3_NUM)) {
                                    $list[] = ($SCR)?$row[0]:$row;
                                }
                                $Result = $list;
                                break;
                            case 'asc':
                                $Result = $myResult->fetchArray(SQLITE3_ASSOC);
                                break;
                        }
                    }
                } else return null;
                $myResult->reset();
            }
        } else {
            $myResult = $this->query('SELECT COUNT(*) FROM ('.$sql.') sqlite_count_trick', 'row', $page);
            if(Application::DEBUG_MODE && !in_array($this->conn_provider->get()->lastErrorCode(), $this->SQLITE_OK_CODES_ARR)){
                Application::LogTxt("\r\n\r\n\r\n".date('d-m-Y H:i:s').": Ошибка SQL #(".$this->conn_provider->get()->lastErrorCode().") [".$this->conn_provider->get()->lastErrorMsg()."]:\r\n".(is_null($page)?$sql:$this->wrapSQL($sql, $page)), "my-sql-errors.log");
            }
            if (in_array($this->conn_provider->get()->lastErrorCode(), $this->SQLITE_OK_CODES_ARR)){
                $Result = ($myResult && $myResult[0]) ? $myResult[0] : $this->conn_provider->get()->changes();
            }
        }

        return $Result;
    }
}