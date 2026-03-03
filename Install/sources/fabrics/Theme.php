<?php
namespace app\pages;

use app\constants\PageResources;
use app\nodes\NodeManager;

class Theme {
    private $name;
    private $css;
    private $js;
    private $header;
    private $footer;
    /**
     * @var Page
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

    /**
     * @return Page
     */
    public function Page(){
        return $this->Context;
    }

    public function getName(){
        return $this->name;
    }

    private function getCssString($link): string
    {
        return '<link rel="stylesheet" type="text/css" href="' . $link . '" media="all" />';
    }
    private function cssTemplate($css){
        $link = PageResources::getFullPathToCSS($css);
        if(is_readable($_SERVER['DOCUMENT_ROOT'].$link)) return $this->getCssString($link);
    }
    public function getCss()
    {
        $tmp = '';
        if (is_array($this->css))
            foreach($this->css as &$css) $tmp .= $this->cssTemplate($css);
        else
            $tmp .= $this->cssTemplate($this->css);

        $nodesCss = NodeManager::getCss();

        foreach ($nodesCss as &$css) {
            $tmp .= $this->getScriptString($css);
        }

        return $tmp;
    }

    public function getFooter()
    {
        $path = PageResources::getFullPathToTemplates($this->footer);
        if(is_readable($path))require_once($path);
    }


    public function getHeader()
    {
        /**
         * @var $CSS
         */
        $path = PageResources::getFullPathToTemplates($this->header);
        if(is_readable($path))require_once($path);
    }

    private function getScriptString($link, $scriptDirective = null): string
    {
        return '<script src="' . $link . '" type="text/javascript"' . ($scriptDirective ? ' '.$scriptDirective : '') . '></script>';
    }
    private function jsTemplate($script){
        //TODO No .js.js protection
        $tmp = stripos($script, '.js')+3;
        $link = PageResources::getFullPathToJS(substr($script, 0, $tmp));
        if(is_readable($_SERVER['DOCUMENT_ROOT'].$link)) return $this->getScriptString($link, (substr($script, $tmp, strlen($script))));
    }
    public function getJs()
    {
        $tmp = '';
        if (is_array($this->js))
            foreach($this->js as &$script) $tmp .= $this->jsTemplate($script);
        else
            $tmp .= $this->jsTemplate($this->js);

        $nodesScripts = NodeManager::getJs();

        foreach ($nodesScripts as &$js) {
            $tmp .= $this->getScriptString($js);
        }

        return $tmp;
    }
}