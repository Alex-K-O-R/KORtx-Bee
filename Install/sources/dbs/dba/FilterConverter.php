<?php
namespace app\filters;

use app\dba\DBAccess;
use app\dba\DBAccessGeneric;
use app\filters\FilterModes;
use app\filters\ModelFilterDescription;
use app\utilities\inner\Array_;
use app\utilities\inner\CIS;
use app\utilities\inner\DataConversion;

interface IFilterToDbConverter {
    static function SupportedOptions();
    static function SQLConditionStructureForPrimitiveField($fieldname, $sign, $value);
}

abstract class FilterConverter extends FilterToDbOperatorConverter implements IFilterToDbConverter {
    public static function SQLConditionStructureForInput($fieldnames, $sign, $values, $lang_id = null) {
        $fieldnames = Array_::varToArray($fieldnames);
        $values = Array_::varToArray($values);
        $result = '';
        if($count = count($fieldnames) > 0 && count($values)){
            switch ($sign) {
                case FilterModes::BOUND :{
                    if($count < 2) return '3=3';
                    $lB = (CIS::l($values, 0, null))?(
                        '('.$fieldnames[0].' <= \''.$values[0].'\' AND '.$fieldnames[1].' >= \''.$values[0].'\'
                    OR '.$fieldnames[0].' >= \''.$values[0].'\')'
                    ):'1=1';

                    $rB = (CIS::l($values, 1, null))?(
                        '('.$fieldnames[0].' <= \''.$values[1].'\' AND '.$fieldnames[1].' >= \''.$values[1].'\'
                    OR '.$fieldnames[1].' <= \''.$values[1].'\')'
                    ):'2=2';

                    $result = $lB.' AND '.$rB;

                } break;
                default:
                    for($i = 0; $i < $count; $i++){
                        if(!$lang_id)
                            //$result .= ','.$fieldnames[$i];
                            //else
                            $result .= ($i>0?' OR ':'').self::SQLConditionStructureForPrimitiveField($fieldnames[$i], $sign, $values[0]/*[$i]*/);
                    }
            }
            $result = '('.$result.')';
            if($lang_id)
                return self::SQLConditionStructureForLanguageRelyingField(implode(',',$fieldnames), $sign, $values[0]);
            else return $result;
        }
        return '';
    }


    public static function SQLConditionStructureForLanguageRelyingField($fieldname, $sign, $value, $lang_id = null){
        return
            ' EXISTS(SELECT string_id from '.DBAccessGeneric::table.'
                WHERE string_id IN ('.$fieldname.') AND '.self::SQLConditionStructureForPrimitiveField('value', $sign, $value).
            (( 1==1 || $lang_id===null)?'':' AND lang_id='.$lang_id).' ORDER BY string_id DESC LIMIT 1)';
    }
}