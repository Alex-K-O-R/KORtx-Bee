<?php
namespace app;

use app\ICoreSettings;
use ReflectionClass;
use app\dba\FileDBA;
use app\pages\Page;
use app\pages\Pages;
use app\pages\ThemeRuler;
use app\dba\LoadDbas;
use app\dba\inners\_WebPortalDBA;
use app\models\inner\LanguageMDL;
use app\models\inner\_UserMDL;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class Profiler {
    private $fileName;
    private $startMark;
    private $critical;

    private $buffer = '';

    function __construct($criticalPeriodOfSeconds = 3){
        $this->fileName = "profiler.log";
        $this->critical = $criticalPeriodOfSeconds;
    }

    public function Start($url = "", $msg = ""){
        $this->startMark = new \DateTime();

        $this->buffer .= "\r\n".$this->startMark->format('d.m.Y H:i:s')."---- Profiler started  for ip ".$_SERVER['REMOTE_ADDR'];
        $this->buffer .= "\r\n on url ".$_SERVER["REQUEST_URI"]." and page ".$url;
        $this->buffer .= "\r\n".$msg;
        $this->buffer .= "\r\n ---- with SESSION ".print_r($_SESSION, true);
    }

    public function Stop($msg = ""){
        $diff = new \DateTime();
        $tmp = "\r\n ".$diff->format('d.m.Y H:i:s')."---- Profiler ended ";
        $diff = $diff->diff($this->startMark);
        $tmp .= ($diff)?("\r\n ---- with a result seconds of ".$diff->format("%s")):"\r\n ---- with a BAD result";
        if( $msg!= "" ){
            $tmp .= " and ".$msg."\r\n";
            Application::LogTxt($this->buffer.$tmp, $this->fileName);
        }
        if($diff && intval(($diff->format("%s")))>$this->critical) {
            Application::LogTxt($this->buffer.$tmp."\r\n WARNING!!! TOO SLOW OPERATION ON url ".$_SERVER['REQUEST_URI'], $this->fileName);
            Application::LogTxt("\r\n\r\n ", $this->fileName);
        }
        $this->buffer='';
    }
}

class ProfilerDummy {
    function _construct($path = null, $criticalPeriodofSeconds = 10){
    }

    public function Start($url = "", $msg = ""){
    }

    public function Stop($msg = ""){
    }
}

abstract class Application implements ICoreSettings {
    // there should be settings trait some day...

    use Core;
    use LoadDbas;
    private $languageModel;

    /**
     * @var LanguageMDL|LanguageMDL[]
     */
    private $languages = null;
    /** Получает информацию о языках, используемых в системе
     * @param $lang_acr
     * @return LanguageMDL|LanguageMDL[]
     */
    public function getSupportedLanguageInfo($lang_acr = null){
        if($this->languages === null) $this->languages = self::processLanguageInfo($this->getWebPortalDBA()->getLanguageInfo());
        if($lang_acr === null){
            return $this->languages;
        }
        $count = count($this->languages);
        for($i=0; $i<$count; $i++) if(mb_strtoupper($this->languages[$i]->getAcronym())==mb_strtoupper($lang_acr)) return $this->languages[$i];
        return null;
    }
    private function processLanguageInfo($res){
        $count = count($res);
        for($i=0; $i<$count; $i++) {
            $j=0;
            $res[$i] = new LanguageMDL($res[$i][$j++], $res[$i][$j++], $res[$i][$j++], $res[$i][$j++], $res[$i][$j]);
        }

        return count($res)===1? $res[0] : $res;
    }


    private $langAcronym;

    public function setLanguage($langAcronym = self::defaultAcronimLang)
    {
        $this->langAcronym = $langAcronym;
    }

    /**
     * @param $indata
     * @param $lang_acronym
     * @return string
     */
    public static function GlobalTransliter($indata, $lang_acronym){
        if(is_string($indata)) return $indata;

        if(isset($indata[$lang_acronym])){return $indata[$lang_acronym];} else {
            if(isset($indata[self::DEFAULT_LANGUAGE_ACRONYM])) {return $indata[self::DEFAULT_LANGUAGE_ACRONYM];}
        }
        foreach ($indata as $indatum) return $indatum; //если нет первода с нужным языком вернем первый вариант перевода
    }

    /**
     * @param $text
     * @return string
     */
    public function Translater($text){
        if($this->getGlobalFilter() && $this->getGlobalFilter()->getPortalLanguageInfo()){
            $acronym = $this->getGlobalFilter()->getPortalLanguageInfo()->getAcronym();
            return $this->GlobalTransliter($text, $acronym);
        } else return $text;
    }

    public function TryGetTranslated($innerResource = null){
        $innerResource = $innerResource ?? $this->page->getPageViewFileName();
        $path = $this->page->getPageBaseDir();

        if($this->getGlobalFilter() && $this->getGlobalFilter()->getPortalLanguageInfo()){
            $acronym = $this->getGlobalFilter()->getPortalLanguageInfo()->getAcronym();
            if(is_file($path.'/'.$acronym.'/'.$innerResource)) return $path.'/'.$acronym.'/'.$innerResource;
            else if(is_file($path.'/'.Application::DEFAULT_LANGUAGE_ACRONYM.'/'.$innerResource)) return $path.'/'.Application::DEFAULT_LANGUAGE_ACRONYM.'/'.$innerResource;
        }

        if(is_file($path.'/'.$innerResource)) return $path.'/'.$innerResource;
        else return false;
    }

    /**
     * @return _UserMDL
     */
    public function getUserInfo()
    {
        return $this->getSessionDiv(Application::SESSION_USER_INFO_BLOCK);
    }

    /**
     * @param $post
     * @return AppFilter
     */
    public function updateGlobalFilter($selected_lang_acr = null, $startDate = null, $endDate = null, $globalConditions = null)
    {
        if($selected_lang_acr===null){
            if ($this->getGlobalFilter()&&$this->getGlobalFilter()->getPortalLanguageInfo()) {
                $selected_lang_acr = $this->getGlobalFilter()->getPortalLanguageInfo()->getAcronym();
            } else {
                $selected_lang_acr = Application::DEFAULT_LANGUAGE_ACRONYM;
            }
        }

        $globalFilter = new AppFilter(
            ($startDate?$startDate:($this->getGlobalFilter()?$this->getGlobalFilter()->getStartDate():null)),
            ($endDate?$endDate:($this->getGlobalFilter()?$this->getGlobalFilter()->getEndDate():null)),
            ($globalConditions?$globalConditions:($this->getGlobalFilter()?$this->getGlobalFilter()->getSelectedSegments():null)),
            $this->getSupportedLanguageInfo($selected_lang_acr)
        );

        $this->refreshSessionDiv(Application::SESSION_GLOBAL_FILTER_INFO_BLOCK, $globalFilter);

        $this->refreshSessionObjects();
    }

    /**
     * @return \app\AppFilter
     */
    public function getGlobalFilter()
    {
        return $this->getSessionDiv(Application::SESSION_GLOBAL_FILTER_INFO_BLOCK);
    }




    public function preloadEnv($path = null, $get = null, $post = null, $file = null)
    {
        if (!CIE::l($this->getGlobalFilter()) || !CIE::l($this->getGlobalFilter()->getPortalLanguageInfo())/*==null||1==1 TODO: проконтроллировать, что ошибка с пустым описателем языка не проявляется после изменения АппФильтра*/)
        {
            /*TODO: remove logs on complete*/
            if(Application::DEBUG_MODE)
                self::__500Report("- и див обновился после start-> после session start с состояния ".print_r($_SESSION, true));
            $this->updateGlobalFilter();
        }
    }

    protected function isSearchDefined($get){
        return CIS::l($get, Application::searchIsRequiredCodeWord);
    }


    protected function setLastSeenMarker($ent_type, $ent_id){
        if(!$this->getUserInfo()) return;
        $this->getWebPortalDBA()->updateLastSeenForEntity(
            $this->getUserInfo()->getUserId(),
            $ent_type, $ent_id
        );
    }

    protected function getLastSeenIdsForEntitySQLPart($ent_type){
        if(!$this->getUserInfo()) return;
        return $this->getWebPortalDBA()->getLastVisitedIdsForUserAndEntitySQL(
            $this->getUserInfo()->getUserId(),
            $ent_type
        );
    }

    /**
     * @param $User _UserMDL
     */
    protected function refreshSessionObjects($User = null){
        if($User){
            $this->refreshSessionDiv(Application::SESSION_USER_INFO_BLOCK, $User);
        }
    }

    /*
        private function updateSessionUserWith($newMdlFetchedSrc){
            $User = ModelProcessor::loadModelsForLanguage(
                'app\models\UserMDL',
                $newMdlFetchedSrc,
                $this->getUserDBA(),
                $this->getGlobalFilter()?$this->getGlobalFilter()->getPortalLanguageInfo()->getLangId():self::DEFAULT_LANGUAGE_ID
            );
            $this->refreshSessionDiv(self::SESSION_USER_INFO_BLOCK, $User);
        }
    */

}

trait Core {
    /**
     * @var ThemeRuler
     */
    private $themes;

    /**
     * @var Page
     */
    private $page;

    protected $Profiler;

    public abstract function preloadEnv($path = null, $get = null, $post = null, $file = null);
    public abstract function loadPage($path = null, $get = null, $post = null, $file = null);

    public function __construct($url) {
        $this->createSession();
        if(Application::PROFILER_MODE) $this->Profiler = new Profiler(); else $this->Profiler = new ProfilerDummy();

        $parsedUrl = parse_url($url);
        $path = CIE::l($parsedUrl, 'path');

        $this->page = new Page($this, $path, $this->processUrl($path));

        if(Application::DEBUG_MODE){
            error_reporting(E_ALL);
            register_shutdown_function('app\Application::__500Report');
        }

        if($path[mb_strlen($path)-1]!=='/' && /*mb_strrpos($path,'.')>mb_strrpos($path, '/')&&*/!is_readable($_SERVER['DOCUMENT_ROOT'].$path)){
            self::__404Header();
            self::__CloseConnectionHeader();
            if(Application::DEBUG_MODE){
                Application::LogTxt("\r\n\r\n ".date('d-m-Y H:i:s').": 404 stop - NO FILE ".$_SERVER['DOCUMENT_ROOT'].$path, 'debug.log');
            }
            $this->Profiler->Stop("STOP Cr. page (404): ".date('d-m-Y H:i:s'));
            exit();
        }
        $this->Profiler->Stop("STOP Cr. page: ".date('d-m-Y H:i:s'));

        $this->preloadEnv($path,
            ($_SERVER['REQUEST_METHOD']==='GET')?((CIS::l($_GET)!=1)?CIS::l($_GET):null):null
            ,($_SERVER['REQUEST_METHOD']==='POST')?CIS::l($_POST):null, CIS::l($_FILES));
        $this->loadPage($path,
            ($_SERVER['REQUEST_METHOD']==='GET')?((CIS::l($_GET)!=1)?CIS::l($_GET):null):null
            ,($_SERVER['REQUEST_METHOD']==='POST')?CIS::l($_POST):null, CIS::l($_FILES));
    }


    /** Up to 10 variables from url.
     * @param $url
     * @param $mask
     * @return array
     */
    private static function extractParamsFromPageUrl($url, $mask){
        $regEx = str_replace('*', '([ _\Q%20\Ea-zA-ZА-Яа-я0-9-]+)', '/^'.str_replace('/', '\/', $mask).'$/');
        $res = array_filter(explode(':', preg_filter($regEx, '$1:$2:$3:$4:$5:$6:$7:$8:$9:$10', $url)));
        foreach($res as &$r){$r = urldecode($r);}

        return array(
            'mask' => $mask,
            'params' => $res
        );
    }

    protected static function processUrl($url) {
        $rc = new ReflectionClass(Pages::TYPE());
        $v = $rc->getConstants();
        $result = null;
        foreach ( $v as $name => $mask){
            if ($mask != $url) {
                $result = self::extractParamsFromPageUrl($url, $mask);
                if (
                    (count($result['params']) == substr_count($mask, '*')) and (count($result['params'])>0)
                ) return $result;
            } else {
                return array(
                    'mask' => $mask
                );
            }
        }
        return null;
    }


    public function Page(){return $this->page;}

    public function RedirectTo($url){
        header("Location: ".$url);
        $this->Profiler->Stop(" redirect to ".$url);
        exit();
    }



    public static function __500Report($custom = null)
    {
        $error = error_get_last();
        if ($custom || $error !== NULL && in_array($error['type'], array(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING |E_RECOVERABLE_ERROR))) {
            self::LogTxt("r\n\r\n".date('d-m-Y H:i:s').": Ошибка [".(($custom)?'user_generated':'debug')."]:\r\n".print_r($custom?$custom:$error, true), "debug.log");
            //die;
        }
    }
    public static function __404Header()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    }

    public static function __CloseConnectionHeader()
    {
        header('Connection: close');
    }


    public static function LogTxt($string, $logFileName){
        $logDir = $_SERVER['DOCUMENT_ROOT'].Application::LOG_DIRECTORY;
        if(Application::DEBUG_MODE && !is_dir($logDir)) FileDBA::createDirectoryWithRights($logDir);
        error_log($string, 3, $logDir.DIRECTORY_SEPARATOR.$logFileName);
    }


    protected abstract function refreshSessionObjects(...$params);

    protected function getSessionDiv($name) {
        if (isset($_SESSION)) {
            return CIS::l($_SESSION, $name);
        }
        return null;
    }

    protected function refreshSessionDiv($name, $obj) {
        $this->unsetSessionDiv($name);
        $this->setSessionDiv($name, $obj);
    }

    protected function setSessionDiv($name, $obj) {
        if (!isset($_SESSION[$name])) {
            $_SESSION[$name] = $obj;
        }
    }

    protected function unsetSessionDiv($name) {
        if(isset($_SESSION)){
            if (CIS::l($_SESSION, $name)) {
                unset($_SESSION[$name]);
            }
        }
    }

    protected function createSession() {
        if (!isset($_SESSION)||isset($_SESSION) && empty($_SESSION)) {
            session_start();
        }
    }

    protected function destroySession() {
        if (isset($_SESSION)) {
            self::unsetSessionDiv(Application::SESSION_USER_INFO_BLOCK);
            self::unsetSessionDiv(Application::SESSION_PORTAL_INFO_BLOCK);
        }
    }
}