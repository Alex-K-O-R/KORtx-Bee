<?php
namespace app\constants;


use app\Application;

class PageResources {
    const pages_path = '/display/pages/';
    const template_path = '/display/general/';
    const css_path = '/display/css/';
    const js_path = '/display/js/';

    public static function getFullPathToPages($file="")
    {
        return $_SERVER['DOCUMENT_ROOT'].PageResources::pages_path.$file;
    }
    public static function getFullPathToTemplates($file="")
    {
        return $_SERVER['DOCUMENT_ROOT'].PageResources::template_path.$file;
    }
    public static function getFullPathToCSS($file="")
    {
        if(mb_strtolower(mb_substr($file,0,4))=='http') return $file;
        return PageResources::css_path.$file;
    }
    public static function getFullPathToJS($file="")
    {
        if(mb_strtolower(mb_substr($file,0,4))=='http') return $file;
        return PageResources::js_path.$file;
    }
}