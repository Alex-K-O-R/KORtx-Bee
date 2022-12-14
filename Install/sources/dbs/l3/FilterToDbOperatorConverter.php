<?php
namespace app\filters;


use app\utilities\inner\CIS;

class FilterToDbOperatorConverter {
    public static function SupportedOptions()
    {
        return array(
            FilterModes::EQUALS,
            FilterModes::STARTS_WITH,
            FilterModes::CONTAINS,
            FilterModes::NOT_EQUALS,
            FilterModes::NOT_STARTS_WITH,
            FilterModes::NOT_CONTAINS,
            FilterModes::BOUND
        );
    }

    static function convertValue($value){
        $logic = array('true'=>1, 'false'=>0);
        if(isset($logic[$value])){$value = $logic[$value];}
        if(!is_bool($value) && !is_numeric($value)) {
            $value = "'".$value."'";
        }
        return $value;
    }

    public static function SQLConditionStructureForPrimitiveField($fieldname, $sign, $value) {
        switch ($sign) {
            case FilterModes::EQUALS :
                return ' '.$fieldname." = ".self::convertValue($value);
            case FilterModes::STARTS_WITH :
                return ' '.$fieldname.((!empty($value))?" LIKE '$value%'":" IS NOT NULL");
            case FilterModes::ENDS_WITH :
                return ' '.$fieldname.((!empty($value))?" LIKE '%$value'":" IS NOT NULL");
            case FilterModes::CONTAINS :
                return ' '.$fieldname.((!empty($value))?" LIKE '%$value%'":" IS NULL");
            case FilterModes::NOT_EQUALS :
                return ' '.$fieldname." <> ".self::convertValue($value);
            case FilterModes::NOT_STARTS_WITH :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '$value%'":" IS NOT NULL");
            case FilterModes::NOT_CONTAINS :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '%$value%'":" IS NOT NULL");
            case FilterModes::NOT_ENDS_WITH :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '%$value'":" IS NOT NULL");
            case FilterModes::MORE :
                return (!empty($value))?' '.$fieldname." > ".self::convertValue($value):"";
            case FilterModes::MORE_EQUAL :
                return (!empty($value))?' '.$fieldname." >= ".self::convertValue($value):"";
            case FilterModes::LESS :
                return (!empty($value))?' '.$fieldname." < ".self::convertValue($value):"";
            case FilterModes::LESS_EQUAL :
                return (!empty($value))?' '.$fieldname." <= ".self::convertValue($value):"";
        }
        return '';
    }
}
?>