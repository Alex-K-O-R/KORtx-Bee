<?php
namespace app\utilities\inner;

use app\utilities\inner\CIE;
class Array_ {
    public static function startsWith(&$haystack, $needle, $removePrefix = false)
    {
        $length = mb_strlen($needle);
        //print_r(mb_substr($haystack, 0, $length).'<>'. $needle);
        $res = (mb_substr($haystack, 0, $length) == $needle);
        if($res && $removePrefix){
            $haystack = mb_substr($haystack, mb_strlen($needle), mb_strlen($haystack)-mb_strlen($needle));
        }
        return $res;
    }

    public static function endsWith(&$haystack, $needle, $removeSuffix = false)
    {
        $length = mb_strlen($needle);
        $res = $length === 0 ||
            (mb_substr($haystack, -$length) == $needle);

        if($res && $removeSuffix){
            $haystack = mb_substr($haystack, 0, -mb_strlen($needle));
        }
        return $res;
    }

    public static function iTrimmedEquals(&$haystack, $needle, $removePrefix = false)
    {
        if(mb_strlen(trim($haystack)) !== mb_strlen(trim($needle))) return false;

        $res = (mb_strtolower(trim($haystack)) == mb_strtolower(trim($needle)));
        if($res && $removePrefix){
            $haystack = mb_substr($haystack, mb_strlen($needle), mb_strlen($haystack)-mb_strlen($needle));
        }
        return $res;
    }

    public static function FilterArrayByKeyName($Array, $filter, $needle, $skipEmpties = false, $preserveSuffixes = false){
        if(count($Array)===0){return null;}
        $tmp = array();
        foreach($Array as $k=>$El){
            if($skipEmpties && !CIE::l($El)) continue;
            if($preserveSuffixes){
                if($filter($k, $needle)) $tmp[$k] = $El;
            } else {
                $res = $filter($k, $needle, true);
                if($res) $tmp[$k] = $El;
            }
        }
        return $tmp;
    }

    public static function checkIfArrayIsMultidimensional($a, $limitFirst = true) {
        foreach ($a as $v) {
            if (is_array($v)) return true;
            if($limitFirst) break;
        }
        return false;
    }

    public static function removeElementsFromArrayByValue( $elements, $array){
        if(!is_array($elements)){$elements = array($elements);}
        foreach($elements as $el){
            array_splice($array, array_search($el, $array ), 1);
        }
        return $array;
    }

    public static function getLastPartAfterDelimeter($stringWDelimeters, $delimeter = '-'){
        if($stringWDelimeters === null){return null;}
        $stringWDelimeters = explode($delimeter, $stringWDelimeters);
        return end($stringWDelimeters);
    }

    public static function arrayAssocToPlainWithIndexes(&$arr){
        foreach($arr as $k=>$val){
            $arr[\utility\Array_::getLastPartAfterDelimeter($k)] = $val;
            unset($arr[$k]);
        }
        return $arr;
    }

    public static function varToArray($to_arr){
        return ($to_arr)?((!is_array($to_arr))?array($to_arr):$to_arr):array();
    }



    public static function getMeanRecords(&$arr, $mean_prefixes){
        $res = array();
        $modified_recs = array();

        //foreach($arr as $rec){
        foreach($mean_prefixes as $prfx){
            $modified_recs += Array_::FilterArrayByKeyName($arr, 'app\utilities\inner\Array_::startsWith', $prfx, false, true);
        }

        return $modified_recs;
        //}
    }

    public static function extractDeleteRecords(&$arr){
        $res = array();

        foreach($arr as $k => $rec){
            if(Array_::endsWith($k, '-new-delete')){
                unset($arr[$k]);
            } else if (Array_::endsWith($k, '-delete', true)){
                $res[Array_::getLastPartAfterDelimeter($k)] = $rec;
            }
        }

        return $res;

    }


    public static function extractAddRecords(&$arr){
        $InformationsToAdd = Array_::FilterArrayByKeyName($arr, 'app\utilities\inner\Array_::endsWith', '-new', false);
        $res = array();
        foreach($InformationsToAdd as $k => $rec){
            $new_k = Array_::getLastPartAfterDelimeter($k);
            if(!CIS::l($res, $new_k)){
                $res[$new_k] = Array_::FilterArrayByKeyName($InformationsToAdd, 'app\utilities\inner\Array_::endsWith', '-'.$new_k, false);;
            } else continue;
        }

        return $res;//array_unique(array_map('utility\Array_::getLastPartAfterDelimeter', array_keys($InformationsToAdd)));
    }


}