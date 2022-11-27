<?php
namespace app\models\inner;


trait DynamicsModelContainer {
    final static function TYPE() {
        return __CLASS__; // <-- Possible because this is trait
    }

    // Dialect-depending field - a field/property that has different value for different language
    // This is links to the dialect-depending fields that are positions of the SQL row result
    // For better visibility dialect-depending properties above are marked with [d_*] prefixes
    public static function getDynamicFieldsIndexes()
    {
        return static::$dynamicFieldsIndexes;
    }
}