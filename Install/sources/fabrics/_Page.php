<?php
namespace app\pages;

use app\Application;
use app\constants\PageResources;
use app\utilities\inner\Array_;
use app\utilities\inner\CIS;

class _Page {
    function __construct($parent, $url = null, $urlProperties = null)
    {
        $this->url = $url;

        $this->urlProperties = $urlProperties;

        $this->themes = new ThemeRuler();
        $this->LoadThemes();

        $this->pageProps = new PageProps();
        $this->Application = $parent;
    }

    /**
     * @var $this Page
     */
    public function LoadThemes(): void
    {
        return;
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
     * @return ThemeRuler
     */
    public function Themes()
    {
        return $this->themes;
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
            $theme = $this->themes->Get($this);
            if ($theme) {
                $theme->getHeader();
            }

            require_once($res_url);

            if ($theme) {
                $theme->getFooter();
            }
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
        $info = Array_::implode('/', $info);
        $info = PageResources::getFullPathToPages().$info;
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