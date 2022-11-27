<?php

namespace app\utilities\inner;
use DateTime;
use app\Application;

class DataConversion {
    public static function DBBooleanConversion($DB_FT_bool){
        return ($DB_FT_bool==='t' || $DB_FT_bool == 1 || $DB_FT_bool=='true')?true:(($DB_FT_bool==='f'|| $DB_FT_bool == 0 || $DB_FT_bool=='false')?false:null);
    }

    public static function DATETIME_formatting($date, $format = null){
        if($date=='0001-01-01 00:00:00 BC'){
            if(Application::DEBUG_MODE){
                Application::LogTxt("\r\nTime error in page: ".$_SERVER['REQUEST_URI'], "my-errors.log");
            }
            return null;
        }
        return ($date==null)?null:(date_format(
            (!($date instanceof DateTime))?new DateTime($date):$date
            , ($format==null)?'Y-m-d':$format
        ));
    }

    public static function TEXT_to_JSON($text){
        return ($text!==null)?addslashes(json_encode($text, JSON_PRETTY_PRINT)):'';
    }
}