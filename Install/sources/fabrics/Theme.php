<?php
namespace app\pages;

use app\constants\ThemeRulerS;
use app\utilities\inner\CIE;

class Theme {
    private $name;
    private $css;
    private $js;
    private $header;
    private $footer;
    /**
     * @var app\Pages\Page
     */
    private $Context;

    function __construct($name, $header_file, $footer_file, $css_file, $js_file)
    {
        if (!$name) return null;
        $this->name = $name;
        $this->css = $css_file;
        $this->footer = $footer_file;
        $this->header = $header_file;
        $this->js = $js_file;
    }


    public function setContext($Page){
        $this->Context = $Page;
        return $this;
    }
    public function Page(){
        return $this->Context;
    }

    public function getName(){
        return $this->name;
    }

    private function cssTemplate($css){
        $link = ThemeRulerS::getFullPathToCSS($css);
        if(is_readable($_SERVER['DOCUMENT_ROOT'].$link))return '<link rel="stylesheet" type="text/css" href="'.$link.'" media="all" />';
    }
    public function getCss()
    {
        $tmp = '';
        if (is_array($this->css))
            foreach($this->css as &$css) $tmp .= $this->cssTemplate($css);
        else
            $tmp .= $this->cssTemplate($this->css);
        return $tmp;
    }

    public function getFooter()
    {
        $path = ThemeRulerS::getFullPathToTemplates($this->footer);
        if(is_readable($path))require_once($path);
    }


    public function getHeader()
    {
        /**
         * @var $CSS
         */
        $path = ThemeRulerS::getFullPathToTemplates($this->header);
        if(is_readable($path))require_once($path);
    }

    private function jsTemplate($script){
        /*$tmp = explode(' ', $script);
        $last = 0;
        for($i = count($tmp); $i > 0; $i--){
            if(Array_::endsWith($tmp[$i], '.js'))
        }
        */
        //TODO No .js.js protection
        $tmp = stripos($script, '.js')+3;
        $link = ThemeRulerS::getFullPathToJS(substr($script, 0, $tmp));
        if(is_readable($_SERVER['DOCUMENT_ROOT'].$link)) return '<script src="'.$link.'" type="text/javascript"'.(substr($script, $tmp, strlen($script))).'></script>';
    }
    public function getJs()
    {
        //if ($this->js)
        $tmp = '';
        if (is_array($this->js))
            foreach($this->js as &$script) $tmp .= $this->jsTemplate($script);
        else
            $tmp .= $this->jsTemplate($this->js);
        return $tmp;
    }
}