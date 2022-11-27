<?php
namespace app\filters;

class FilterModes {
    const EQUALS = 'e';//"equals";
    const STARTS_WITH = 'sW';//"startsWith";
    const ENDS_WITH = 'eW';//endsWith";
    const CONTAINS = 'c';//"contains";
    const NOT_EQUALS = 'nE';//"notEquals";
    const NOT_STARTS_WITH = 'nsW';//"notStartsWith";
    const NOT_CONTAINS = 'nC';//"notContains";
    const NOT_ENDS_WITH = 'neW';//"notEndsWith";
    const MORE = 'm';//More;
    const MORE_EQUAL = 'mE';//MoreEqual;
    const LESS = 'l';//Less;
    const LESS_EQUAL = 'lE';//LessEquall;
    const BOUND = 'bD';//LessEquall;
}

class ConditionsJoinSQLType {
    const OR_ = ' OR ';
    const AND_ = ' AND ';
}