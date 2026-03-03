<?php


namespace app\utilities\inner;


class Strings
{
    public static function iTrimmedEquals(&$haystack, $needle, $removePrefix = false)
    {
        if (mb_strlen(trim($haystack)) !== mb_strlen(trim($needle))) return false;

        $res = (mb_strtolower(trim($haystack)) == mb_strtolower(trim($needle)));
        if ($res && $removePrefix) {
            $haystack = mb_substr($haystack, mb_strlen($needle), mb_strlen($haystack) - mb_strlen($needle));
        }
        return $res;
    }

    public static function startsWith(&$haystack, $needle, $removePrefix = false)
    {
        $length = mb_strlen($needle);
        $res = (mb_substr($haystack, 0, $length) == $needle);
        if ($res && $removePrefix) {
            $haystack = mb_substr($haystack, mb_strlen($needle), mb_strlen($haystack) - mb_strlen($needle));
        }
        return $res;
    }

    public static function endsWith(&$haystack, $needle, $removeSuffix = false)
    {
        $length = mb_strlen($needle);
        $res = $length === 0 ||
            (mb_substr($haystack, -$length) == $needle);

        if ($res && $removeSuffix) {
            $haystack = mb_substr($haystack, 0, -mb_strlen($needle));
        }
        return $res;
    }

    public static function getLastPartAfterDelimeter($stringWDelimeters, $delimeter = '-')
    {
        if ($stringWDelimeters === null) {
            return null;
        }
        $stringWDelimeters = explode($delimeter, $stringWDelimeters);
        return end($stringWDelimeters);
    }
}