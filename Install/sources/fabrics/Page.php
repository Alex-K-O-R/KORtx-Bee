<?php
namespace app\pages;

use app\Application;
use app\constants\ThemeRulerS;
use app\utilities\inner\CIS;

class Page {
    use PageThemes;
    function __construct($parent, $url = null, $urlProperties = null)
    {
        $this->url = $url;

        $this->urlProperties = $urlProperties;

        $this->themes = new ThemeRuler();
        $this->LoadThemes();

        $this->pageProps = new PageProps();
        $this->Application = $parent;
    }

    private $url;

    private $urlProperties = array();

    private $viewInfo = array(
    );

    public function Url($getRequestString=null, $url=null)
    {
        if ($getRequestString === null && $url === null) {return $this->url;}
        else {
            $getRequestString = ($getRequestString!=null && $getRequestString[0]!='?')?'?'.$getRequestString:$getRequestString;
            return (($url === null)?($this->url.$getRequestString):($url.$getRequestString));
        }
    }

    public function getPageMask()
    {
        return $this->urlProperties['mask'];
    }

    public function getPageUrlParameters()
    {
        return $this->urlProperties['params'];
    }



    /**
     * @var ThemeRuler
     */
    private $themes;

    /**
     * @var $Application Application
     */
    private $Application;

    private $props;

    /**
     * @var PageProps
     */
    private $pageProps;

    /**
     * @return \app\pages\PageProps
     */
    public function Properties()
    {
        return $this->pageProps;
    }

    public function Application(){
        return $this->Application;
    }


    /**
     * @param $theme_name
     * @return $this
     */
    public function UseTheme($theme_name){
        $this->themes->getThemeByName($theme_name);
        return $this;
    }

    public function Theme(){
        return $this->themes->Get($this);
    }

    public function Display($view){
        $this->extractViewInfo($view);
        $res_url = $this->Application()->TryGetTranslated();

        if($res_url){
            $this->themes->Get($this)->getHeader();
            require_once($res_url);
            $this->themes->Get($this)->getFooter();
        } else
            $this->Application->RedirectTo('/404');
    }

    private function extractViewInfo($pageView){
        $info = explode('/', $pageView);

        $file = $info[count($info)-1];
        if($file == '') $file = 'index.php';
        $this->viewInfo["file"] = $file;

        $info = array_slice($info, 0, count($info)-1);
        $info = array_filter($info);
        $info = implode('/', $info);
        $info = ThemeRulerS::getFullPathToPages().$info;
        $this->viewInfo["base"] = $info;

        return $info;
    }

    public function getPageBaseDir()
    {
        return CIS::l($this->viewInfo, "base", null);
    }


    public function getPageViewFileName()
    {
        return CIS::l($this->viewInfo, "file", null);
    }


}