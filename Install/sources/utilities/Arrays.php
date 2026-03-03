<?php
namespace app\utilities\inner;

use app\utilities\inner\CIE;

class Arrays {
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

    public static function arrayAssocToPlainWithIndexes(&$arr){
        foreach($arr as $k=>$val){
            $arr[Strings::getLastPartAfterDelimeter($k)] = $val;
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
            $modified_recs += Arrays::FilterArrayByKeyName($arr, 'Strings::startsWith', $prfx, false, true);
        }

        return $modified_recs;
        //}
    }

    public static function extractDeleteRecords(&$arr){
        $res = array();

        foreach($arr as $k => $rec){
            if(Strings::endsWith($k, '-new-delete')){
                unset($arr[$k]);
            } else if (Strings::endsWith($k, '-delete', true)){
                $res[Strings::getLastPartAfterDelimeter($k)] = $rec;
            }
        }

        return $res;

    }

    public static function extractAddRecords(&$arr){
        $InformationsToAdd = Arrays::FilterArrayByKeyName($arr, 'Strings::endsWith', '-new', false);
        $res = array();
        foreach($InformationsToAdd as $k => $rec){
            $new_k = Strings::getLastPartAfterDelimeter($k);
            if(!CIS::l($res, $new_k)){
                $res[$new_k] = Arrays::FilterArrayByKeyName($InformationsToAdd, 'Strings::endsWith', '-'.$new_k, false);;
            } else continue;
        }

        return $res;
    }

    public static function implode($a, $b){
        $result = null;

        if(is_array($a) && is_string($b)) {
            $result = (PHP_MAJOR_VERSION < 8 && PHP_MINOR_VERSION < 4) ? implode($a, $b) : implode($b, $a);
        }

        if(is_string($a) && is_array($b)) {
            $result = (PHP_MAJOR_VERSION < 8 && PHP_MINOR_VERSION < 4) ? implode($b, $a) : implode($a, $b);
        }

        return $result;
    }
}