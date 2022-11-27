<?php
namespace app\utilities\inner {

    // Класс используется для проверки переменной на непустоту.
    class CIE {
        public static function l($a, $ind = null){
            if (!isset($a)) return null;
            if (isset($ind)) {
                if(!isset($a[$ind])) return null;
                return (!empty($a[$ind])||$a[$ind]==='0'||$a[$ind]===0&&$a[$ind]===false)?$a[$ind]:null;
            } else {
                if(is_string($a)) {$a = trim($a);}
                return (!empty($a)||$a==='0'||$a===0||$a===false)?$a:null;
            }
        }
    }

    // Класс используется для проверки переменной на определенность.
    class CIS {
        /**
         * @param $a
         * @param null $ind
         * @param $RIFSBE //means return if isset but empty - default true
         * @return bool|null
         */
        public static function l($a, $ind = null, $RIFSBE = true){
            if (!isset($a)) return null;
            if (isset($ind)) {
                //print var_export($ind, true)." is ".var_export($a[$ind], true)." <br/>";
                return (isset($a[$ind]))?((empty($a[$ind])&&$a[$ind]!=='0'&&$a[$ind]!==0&&$a[$ind]!==false)?$RIFSBE:$a[$ind]):null;
            } else {
                return ((empty($a)&&$a!=='0'&&$a!==0&&$a!==false)?$RIFSBE:$a);
            }
        }
    }


    // Класс используется для взаимодействия с чекбоксами HTML.
    class CIC {
        public static function l($a, $ind = null){
            if (!isset($a)) return false;
            if($ind === null) return true;
            else
                if (!isset($a[$ind])) return false;
                else
                    return (($a[$ind] !== '')?true:false);
        }
    }

    class CICp {
        public static function l($a, $ind = null){
            return (CIC::l($a, $ind))?true:false;
        }
    }
}