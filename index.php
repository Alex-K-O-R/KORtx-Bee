<?php
$showErrorsAndWarningsKey = 'debug';
if(isset($_GET)&&isset($_GET[$showErrorsAndWarningsKey])||isset($_SERVER['HTTP_REFERER'])&&stristr($_SERVER['HTTP_REFERER'], '?'.$showErrorsAndWarningsKey)){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_USER_DEPRECATED);
    ini_set('display_errors', 0);
    ini_set('display_warnings', 0);
    ini_set('display_notices', 0);
}
/*
if(isset($_GET['a'])||isset($_GET['username'])){
    include_once($_SERVER['DOCUMENT_ROOT'].'/Install/utilities/dbclient/adminer.php');
    exit;
}
*/
if(!is_file($_SERVER['DOCUMENT_ROOT'].'/Install/Done.txt')){
    include_once($_SERVER['DOCUMENT_ROOT'].'/Install/index.php');
} else {
    //if (($_SERVER['REQUEST_URI']==='/') and (($_SERVER['REMOTE_ADDR']==='84.53.246.198') or (isset($_SESSION['is'])) and ($_SESSION['is']=='first_lookup'))) {
        //$site = new Application($_SERVER['REQUEST_URI']);
    //}
    //if(Array_::startsWith($_SERVER['HTTP_HOST'], 'api.')){
        //require_once $_SERVER['DOCUMENT_ROOT'] . '/APILoader.php';
        //$site = new APIPart($_SERVER['REQUEST_URI']);
    //} else {
    include_once($_SERVER['DOCUMENT_ROOT'].'/Loader.php');
    $site = new app\WebUIApplication($_SERVER['REQUEST_URI']);
}
?>


