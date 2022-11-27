<?php
namespace app\utilities\inner;

class CurrencyDescription {
    public $courseToDefault;
    public $shortName;

    function __construct($courseToDefault, $shortName)
    {
        $this->courseToDefault = $courseToDefault;
        $this->shortName = $shortName;
    }
}

class Currency {
    const RUB = 1;
    const USD = 2;
    const EUR = 3;
    const AED = 4;

    static function get($type){
        switch($type){
            case self::RUB:{
                return new CurrencyDescription(1, 'RUB');
            }
            break;

            case self::USD:{
                return new CurrencyDescription(68.3, 'USD');
            }
            break;

            case self::EUR:{
                return new CurrencyDescription(76.4, 'EUR');
            }
            break;

            case self::AED:{
                return new CurrencyDescription(16.8, 'AED');
            }
            break;

            default: return null;
        }
    }
}