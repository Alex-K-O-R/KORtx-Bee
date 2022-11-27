<?php
namespace app\pages;


class PageFinder {
    final static function TYPE() {
        return get_called_class();
    }

    public static function getDynamic($pageMask, array $attributes = null) {
        $pageMaskParts = explode('*', $pageMask);
        $url = '';
        $i = 0;
        $j = 0;
        $total_ins = (count($pageMaskParts)-1);
        foreach($pageMaskParts as &$part) {
            $attribute = '';
            if (isset($attributes[$i])) {
                if (is_array($attributes[$i])) {
                    if (isset($attributes[$i][$j])) $attribute = $attributes[$i][$j]; else
                    {
                        $i++;
                        $attribute = $attributes[$i];
                    }
                    $j++;
                    $i--;
                } else {
                    if (isset($attributes[$i])) $attribute = $attributes[$i];
                }
            }
            $url .= $part;
            $url .= ($attribute!='' && ($i+$j)==$total_ins)?'?'.$attribute:$attribute;
            $i++;
        }
        return $url;
    }
}