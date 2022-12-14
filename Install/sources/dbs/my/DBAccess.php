<?php
namespace app\dba;
use app\Application;
use app\Core;
use app\dba\constants\DBChanges;
use app\dba\constants\DBSettings;
use app\utilities\inner\CIS;

class DBAccess extends DBAccessGeneric {

    protected function UPDATE_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table)
    {
        $keyField = $keyField ?? $returningCol;
        $base_sql .= ' AND '.$keyField.' = LAST_INSERT_ID('.$keyField.')';
        $base_sql = $this->INSERT_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField, $table);
        return $base_sql;
    }

    protected function INSERT_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table)
    {
        $keyField = $keyField ?? $returningCol;

        $base_sql .= ';';
        $base_sql .= ' SELECT '.$returningCol.' FROM '.$table.' WHERE '.$keyField.' = LAST_INSERT_ID();';
        return $base_sql;
    }

    public function escape_string($string)
    {
        return mysqli_escape_string($this->conn_provider->get(), $string);
    }


    /**$query = self::historicalUpdate(self::table, 'value', $value, 'string_id', $string_id, $lang_id);
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
        if(!is_bool($value) && !is_numeric($value)) {
            $value = "'".$value."'";
        }

        return $this->query(
            "set @prevval = '';
        update $table
            set $field = if(@prevval := $field , $value, $value)
            where $keyField = $keyVal ".($lang_id === null?'':' AND lang_id = '.$lang_id).";
        select @prevval;
        "
            , 'row');
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
                    SELECT LAST_INSERT_ID(string_id), '.$lang_id.', \''.$value.'\', \''.$ent_type.'\' FROM existing'
                    , 'string_id')
                , 'row'
            );
        } else {
            $query = $this->query(
                $this->INSERT_RETURNING_TRICK_SQL('INSERT INTO '.self::table.' (string_id, lang_id, value, ent_type)
                VALUES(LAST_INSERT_ID('.intval($string_id).'), '.$lang_id.', \''.$value.'\', \''.$ent_type.'\')
                ', 'string_id')
                , 'row');
        }

        if ($query) {
            return $query[0];
        } else return null;
    }


    final function query($sql, $fetch = null, Page $page = null) {
        if (is_null($page)) {
            $myResult = mysqli_multi_query($this->conn_provider->get(), $sql);
        } else {
            $myResult = mysqli_multi_query($this->conn_provider->get(), $this->wrapSQL($sql, $page));
        }

        if(Application::DEBUG_MODE && mysqli_error($this->conn_provider->get())){
            Application::LogTxt("\r\n\r\n\r\n".date('d-m-Y H:i:s').": Ошибка SQL [".mysqli_error($this->conn_provider->get())."]:\r\n".is_null($page)?$sql:$this->wrapSQL($sql, $page), "my-sql-errors.log");
        }

        $Result = null;

        if (!mysqli_error($this->conn_provider->get()) && $myResult){
            do {
                if ($res = $this->conn_provider->get()->store_result()) {
                    $myResult = $res;
                }
            } while ($this->conn_provider->get()->more_results() && $this->conn_provider->get()->next_result());

            if (is_bool($myResult)) {$Result = $myResult;} else {
                if (is_null($fetch)) {
                    $Result = mysqli_fetch_all($myResult);
                } else {
                    switch($fetch){
                        case 'row':
                            while ($row = mysqli_fetch_row($myResult)) {
                                if($row) $Result = $row;
                            }

                            break;
                        case 'arr': //DONE! TODO : Perhaps will be cool if mixed with *set* method when single collumn appears..
                            $list = array();
                            while ($row = mysqli_fetch_row($myResult)) {
                                $list[] = $row;
                            }
                            $Result = $list;
                            break;
                        case 'aff':
                            $Result = mysqli_affected_rows($this->conn_provider->get());
                            break;
                        case 'asc':
                            $Result = mysqli_fetch_assoc($myResult);
                            break;
                    }
                }
                mysqli_free_result($myResult);
            }
        } else return null;

        return $Result;
    }
}