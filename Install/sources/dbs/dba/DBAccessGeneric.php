<?php
namespace app\dba;

use app\Application;
use app\dba\constants\DBSettings;
use app\Core;
use app\dba\constants\DBChanges;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

interface IModelDataProvider {
    public function GLOBAL_getAllForAllSQL();
    static function EntityCode($codeLength = 3);
}

abstract class DBAccessGeneric {
    const table = DBSettings::dbprfx.'_strings';
    const AND_=',';

    protected $conn_provider;

    public function __construct($useDefaultDb = true){
        $this->conn_provider = new DBSettings($useDefaultDb);
    }

    public abstract function query($sql, $fetch = null, Page $page = null);

    /** These monsters should be removed in time, when ... All SQLs will have much more in common, and RETURNINGs will become available anywhere. Like in PostgreSQL!!! Thumbs up, PG!
     * @param $base_sql
     * @param $returningCol
     * @param null $keyField
     * @param string $table
     * @return mixed
     */
    protected abstract function UPDATE_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table);
    protected abstract function INSERT_RETURNING_TRICK_SQL($base_sql, $returningCol, $keyField = null, $table = self::table);


    protected abstract function historicalUpdate($table, $field, $value, $keyField, $keyVal, $lang_id);

    public abstract function escape_string($string);

    protected function wrapSQL($sql, Page $page){
        /* TODO: check if * may break columns order of subquery
         It's either this below, or some hack via json or nice future with *exclude* columns possibility.

        $row = $this->query('SELECT * FROM ('.$sql.') p LIMIT 1', 'asc');

        if($row){ TODO: check if code is okay =[0]
            //$row = $row[0];
            return 'SELECT '.implode(',',array_keys($row)).'*/
        return 'WITH base_sql as (
                    SELECT *, ROW_NUMBER() OVER (ORDER BY '.(($page->getOrderByField())?'wRows.'.$page->getOrderByField():'1').' '.(($page->getOrderDirection())?$page->getOrderDirection():'').') as rNum
                         FROM  (
                                   '.$sql.'
                         ) as wRows
                    )
                    SELECT * FROM base_sql WHERE rNum > '.(($page->getPageNumber()-1)*$page->getPageSize()).'
                    AND rNum <= '.($page->getPageNumber()*$page->getPageSize()).'
                    ORDER BY rNum';
        /*}
        return $sql;*/
    }



    final protected static function fields_from($fields, $from){
        if (is_string($fields)) $fields = explode(',', $fields);
        return implode(preg_filter('/^/', $from.'.', $fields), self::AND_);
    }


    final function getStringDynamicsByStringId($lang_id, $stringIds, $ent_id = null){
        if(!$stringIds) return null;
        $ent_id = null; //TEMPORARY OFF UNTIL DONE
        if(!is_array($stringIds)) $stringIds = array($stringIds);
        return $this->query(
            'SELECT string_id, lang_id, value FROM '.self::table.' WHERE 1=1 '
                .($ent_id === null?'':' AND entity_id = \''.$ent_id.'\'')
                .($lang_id === null?'':' AND lang_id = \''.$lang_id.'\'')
                .' AND string_id IN ('.implode(',', $stringIds).') ORDER BY string_id'
            , 'arr');
    }

    final protected function checkIfDynamicStringStillHasAnotherLanguages($stringIds, $ent_id = null){
        $ent_id = null; //TEMPORARY OFF UNTIL DONE
        if(!is_array($stringIds)) $stringIds = array($stringIds);
        return $this->query(
            'SELECT string_id, count(lang_id) FROM '.self::table.'
            WHERE 2=2 '.($ent_id === null?'':' AND entity_id = \''.$ent_id.'\'').'
            and string_id IN ('.implode(',', $stringIds).')
            --AND lang_id <> 0
            GROUP BY string_id', 'arr');
    }

    final static function GLOBAL_getFirstEmptyIndexFromNonAutoIncrementTableSQL($indxFieldName, $tblName){
        return '(select case when NOT
                EXISTS(SELECT NULL FROM '.$tblName.')
                then 0 else (
                        SELECT '.$indxFieldName.'+1 FROM '.$tblName.' t1
                 where not exists (
                            select '.$indxFieldName.' from '.$tblName.' t2 where
                  t1.'.$indxFieldName.' IS NOT NULL AND t2.'.$indxFieldName.' IS NOT NULL
                            AND (t1.'.$indxFieldName.'+1) = t2.'.$indxFieldName.'
                  ORDER BY t1.'.$indxFieldName.' ASC limit 1
                 )
                 ORDER BY '.$indxFieldName.' ASC limit 1
                ) end  as '.$indxFieldName.')';
    }



    final protected function updateDynamicStringById($string_id, $lang_id, $value = ''){
        $query = self::historicalUpdate(self::table, 'value', $value, 'string_id', $string_id, $lang_id);
        if ($query) {
            return $query[0];
        } else return null;
    }


    final protected function deleteDynamicStringById($string_id, $lang_id){
        $row = $query = $this->query('DELETE FROM '.self::table.'
            WHERE 4=4 AND string_id = \''.$string_id.'\' AND lang_id = \''.$lang_id.'\'
            RETURNING value', 'row');
        if($row) return $row[0]; else return false;
    }

    final protected function deleteDynamicStringsForAllLanguagesByIds($string_ids, $ent_type=null, $ent_id=null){
        Application::LogTxt('DELETE FROM '.self::table.'
            WHERE 5=5 AND string_id IN ('.self::formStringIds($string_ids).') AND  ent_type = \''.$ent_type.'\'', 'my-sql-errors.log');
        $this->query('DELETE FROM '.self::table.'
            WHERE 5=5 AND string_id IN ('.self::formStringIds($string_ids).') AND  ent_type = \''.$ent_type.'\'');// AND entity_id = \''.$ent_id.'\'
        //');
    }

    final protected function deleteDynamicStringsForSpecificLanguageByIds($string_ids, $lang_id = null){
        if($lang_id===null){return null;}
        return $this->query('DELETE FROM '.self::table.'
            WHERE 6=6 AND lang_id = \''.$lang_id.'\' AND string_id IN ('.self::formStringIds($string_ids).')', 'aff');// AND entity_id = \''.$ent_id.'\' AND  ent_type = \''.$ent_type.'\'
        //');
    }

    private function formStringIds($string_ids){
        if(!is_array($string_ids)) $string_ids = array($string_ids);
        $string_ids = array_filter($string_ids, function($a){if(isset($a)&&$a!='') return $a;});
        if(count($string_ids)<1) return null;
        return implode(',', $string_ids);
    }

    final protected function deleteAllDynamicStringsForEntity($ent_id, $ent_type, $delAck){
        if($delAck!==true) return null;
        $query = $this->query('DELETE FROM '.self::table.'
            WHERE 0=0 AND ent_type = \''.$ent_type.'\' AND entity_id = \''.$ent_id.'\'
        ');
    }

    final function getStringDynamicsIdsByStringContains($lang_id, $values, $filterStyle, $ent_id = null){
        return $this->query(
            'SELECT string_id, value FROM '.self::table.' WHERE 7=7 '
                .($ent_id === null?'':' AND entity_id = \''.$ent_id.'\'')
                .' AND lang_id = \''.$lang_id.'\''
                .' AND value IN ('.implode(',', $values).')'
            , 'arr');
    }

}