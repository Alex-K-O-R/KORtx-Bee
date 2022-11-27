<?php
namespace app\filters;


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

    public static function SQLConditionStructureForPrimitiveField($fieldname, $sign, $value) {
        switch ($sign) {
            case FilterModes::EQUALS :
                return ' '.$fieldname." = '$value'";
            case FilterModes::STARTS_WITH :
                return ' '.$fieldname.((!empty($value))?" LIKE '$value%'":" IS NOT NULL");
            case FilterModes::ENDS_WITH :
                return ' '.$fieldname.((!empty($value))?" LIKE '%$value'":" IS NOT NULL");
            case FilterModes::CONTAINS :
                return ' '.$fieldname.((!empty($value))?" LIKE '%$value%'":" IS NULL");
            case FilterModes::NOT_EQUALS :
                return ' '.$fieldname." <> '$value'";
            case FilterModes::NOT_STARTS_WITH :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '$value%'":" IS NOT NULL");
            case FilterModes::NOT_CONTAINS :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '%$value%'":" IS NOT NULL");
            case FilterModes::NOT_ENDS_WITH :
                return ' '.$fieldname.((!empty($value))?" NOT LIKE '%$value'":" IS NOT NULL");
            case FilterModes::MORE :
                return (!empty($value))?' '.$fieldname." > '$value'":"";
            case FilterModes::MORE_EQUAL :
                return (!empty($value))?' '.$fieldname." >= '$value'":"";
            case FilterModes::LESS :
                return (!empty($value))?' '.$fieldname." < '$value'":"";
            case FilterModes::LESS_EQUAL :
                return (!empty($value))?' '.$fieldname." <= '$value'":"";
        }
        return '';
    }
}
?>