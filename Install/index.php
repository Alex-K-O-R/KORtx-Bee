<?php
set_time_limit(1800);

$InstallationSourcePath = $_SERVER['DOCUMENT_ROOT'].'/Install';
$usrCrPath = $_SERVER['DOCUMENT_ROOT'].'/core'; // this is extensions path, user should do things and wonders right there
$rootCrPath = $usrCrPath.'/_core'; // this is auto generated files by the installation, files there should not be changed


include_once($InstallationSourcePath.'/sources/dbs/dba/classes/FileDBA.php');
include_once($InstallationSourcePath.'/sources/utilities/CIES.php');
include_once($InstallationSourcePath.'/constants/AppSettings.php');
include_once($InstallationSourcePath.'/demo/core/dba/DbaLoader.php');
include_once($InstallationSourcePath.'/sources/Application.php');

use app\dba\FileDBA;
use app\utilities\inner\CIS;
use app\Application;

session_start();
error_reporting(E_CORE_ERROR, E_ERROR, E_PARSE, E_USER_ERROR);
//error_reporting(E_ALL);
ini_set('display_errors', 1);

if(($lang = CIS::l($_GET, 'lang'))){
    $_SESSION['lang'] = $_GET['lang'];
}
if(!($lang = CIS::l($_SESSION, 'lang'))){
    $lang = 'EN';
}

$step = CIS::l($_POST, 'step', 0);

// Step 1.
$project_url = CIS::l($_POST, 'project_url', null);
if($project_url && $project_url[mb_strlen($project_url)-1]=='/')$project_url = mb_substr($project_url, 0, -1);

// Step 2.
$db_host = CIS::l($_POST, 'db_host', null);
$db_user = CIS::l($_POST, 'db_user', null);
$db_pass = CIS::l($_POST, 'db_pass', null);
$db_type = CIS::l($_POST, 'db_type', null);

// Step 3.
$project_keyword = CIS::l($_POST, 'project_keyword', null);

// Step 4.
$adm_pass = CIS::l($_POST, 'adm_pass', null);
$adm_login = CIS::l($_POST, 'adm_login', null);


$db_sec = $InstallationSourcePath.'/sources/dbs';
$db_src = null;
if($db_type == 'pgsql'){
    $db_src = $db_sec.'/pg';
}
if($db_type == 'mysql'){
    $db_src = $db_sec.'/my';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="/Install/display/css/form.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/Install/display/css/installation.css" media="all" />
    <script src="/Install/demo/display/js/jquery.min.js" type="text/javascript"></script>
    <script src="/Install/demo/display/js/KORtx/KShowPassword.js" type="text/javascript"></script>
    <title>KORtx Bee Installation</title>
</HEAD>
<BODY>
<main id="expo-pages" class="page-registration">
    <div class="container">
        <ul class="breadcrumbs">
            <li class="breadcrumbs-item"><a href="/?lang=RU" class="breadcrumbs-link">Русский</a></li>
            <li class="breadcrumbs-item"><a href="/?lang=EN" class="breadcrumbs-link">English</a></li>
        </ul>
    </div>
    <!--

    <h5>-- <?=$step?> --</h5>

    -->
    <form method="POST" action="/" class="ui small form kortx reg form">
<?
$InstallationIsFinished = false;

switch($step){
    case 0:
        include_once($InstallationSourcePath.'/steps/1/index.php');
    break;

    case 1:
        if($project_url){
            FileDBA::createDirectoryWithRights($usrCrPath);
            FileDBA::createDirectoryWithRights($rootCrPath);
            copy($InstallationSourcePath.'/inc/This _core section is autogenerated.txt', $rootCrPath.'/This _core section is autogenerated.txt');
            if(!is_readable($rootCrPath.'/This _core section is autogenerated.txt')){
                echo(
                Application::GlobalTransliter(
                    array(
                        'RU'=>'Ошибка. Что-то пошло не так - не удаётся создать/прочитать целевой файл в директории. Вероятны ошибки в настройках прав доступа ОС.'
                    , 'EN'=>'Error. Something went wrong - unable to create/read file in target directory. Possible reasons are misses in access rights settings in OS.'), $lang
                )
                );
                return;
            }
            $copies = FileDBA::getFilesPathsListOfTypeInDirectory($InstallationSourcePath.'/sources/utilities');
            FileDBA::createDirectoryWithRights($rootCrPath.'/utilities');
            foreach($copies as $cpy){
                $dest = $rootCrPath.'/utilities/'.basename($cpy);
                copy($cpy, $dest);
            }
            include_once($InstallationSourcePath.'/steps/2/index.php');
        } else {
            include_once($InstallationSourcePath.'/steps/1/index.php');
        }
    break;



    case 2:
        if($db_host && $db_user && $db_pass && $db_type){
            if(is_dir($db_src)){
                $copies = FileDBA::getFilesPathsListOfTypeInDirectory($InstallationSourcePath.'/constants');
                FileDBA::createDirectoryWithRights($_SERVER['DOCUMENT_ROOT'].'/constants');
                foreach($copies as $cpy){
                    $dest = $_SERVER['DOCUMENT_ROOT'].'/constants/'.basename($cpy);
                    copy($cpy, $dest);
                }
                FileDBA::createDirectoryWithRights($rootCrPath.'/dba');
                copy($db_src.'/DBAccess.php', $rootCrPath.'/dba/DBAccess.php');
                copy($db_src.'/FilterToDbOperatorConverter.php', $rootCrPath.'/dba/FilterToDbOperatorConverter.php');
                copy($db_src.'/DBSettings.php', $_SERVER['DOCUMENT_ROOT'].'/constants/DBSettings.php');
                FileDBA::replaceTmpltsInFile(
                    $_SERVER['DOCUMENT_ROOT'].'/constants/DBSettings.php'
                    , array(
                        'adr' => $db_host
                        ,'usr' => $db_user
                        ,'pass' => $db_pass
                    )
                    , true
                );
                include_once($InstallationSourcePath.'/steps/3/index.php');
            }
        } else {
            include_once($InstallationSourcePath.'/steps/2/index.php');
        }

    break;

    case 3:
        if($project_keyword){
            $copies = FileDBA::getFilesPathsListOfTypeInDirectory($InstallationSourcePath.'/sources/dbs/dba');
            foreach($copies as $cpy){
                copy($cpy, $rootCrPath.'/dba/'.basename($cpy));
            }
            FileDBA::replaceTmpltsInFile(
                $_SERVER['DOCUMENT_ROOT'].'/constants/DBSettings.php'
                , array('prefix' => $project_keyword)
                , true
            );

            include_once($InstallationSourcePath.'/inc/include_dbconstants.php');

            $DBAccess = new \app\dba\DBAccess(false);

            $sqlPrep = file_get_contents($db_src.'/kortx_db_default_settings.sql');
            $sqlPrep = str_replace('{prefix}', $project_keyword, $sqlPrep);

            // this --> $sqlPrep = substr($sqlPrep, strpos($sqlPrep, "\n") + 1);
            $sqlPrep = preg_split("/\r\n|\n|\r/", $sqlPrep);
            $sqlPrep = array_filter($sqlPrep);
            array_shift($sqlPrep); // <-- or that is required, cos file_get_contents adds strange symbol '﻿﻿﻿﻿﻿﻿' (try to move with arrows upon the quotes) in the beginning

            foreach($sqlPrep as $sql) {
                $DBAccess->query($sql);
            }
            unset($DBAccess);

            $DBAccess = new \app\dba\DBAccess();
            $sqlPrep = $db_src.'/setup';

            $sql = file_get_contents($sqlPrep.'/kortx_languages.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_geography_common.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_strings.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_log.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);

            include_once($InstallationSourcePath.'/steps/4/index.php');
        } else {
            include_once($InstallationSourcePath.'/steps/3/index.php');
        }
    break;

    case 4:
        if($adm_login && $adm_pass){
            include_once($InstallationSourcePath.'/inc/include_dbconstants.php');

            $DBAccess = new \app\dba\DBAccess();
            $sqlPrep = $db_src.'/setup';

            $sql = file_get_contents($sqlPrep.'/kortx_security.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_favorites.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_last_seen.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);
            $sql = file_get_contents($sqlPrep.'/kortx_userinfo.sql');
            $sql = str_replace('{prefix}', $project_keyword, $sql);
            $DBAccess->query($sql);

            $copies = FileDBA::getFilesPathsListOfTypeInDirectory($InstallationSourcePath.'/sources/dbs/dba/classes');
            FileDBA::createDirectoryWithRights($rootCrPath.'/dba/classes');
            foreach($copies as $cpy){
                $dest = $rootCrPath.'/dba/classes/'.basename($cpy);
                copy($cpy, $dest);
            }

            $mdl_src = $InstallationSourcePath.'/sources/models';
            FileDBA::createDirectoryWithRights($rootCrPath.'/dba/models');
            FileDBA::copyRecursive($mdl_src, $rootCrPath.'/dba/models');

            $dem_cr_src = $InstallationSourcePath.'/demo/core';
            FileDBA::createDirectoryWithRights($usrCrPath);

            FileDBA::copyRecursive($dem_cr_src, $usrCrPath);

            include_once($InstallationSourcePath.'/inc/include_dbas.php');

            $CrUsrDba = new \app\dba\UserDBA();
            $DBModCntxt = new \app\dba\DBModificationContext(NULL,NULL,$CrUsrDba, \app\dba\constants\DBChanges::auto);

            $userId = $CrUsrDba->addUser($adm_login, $adm_pass, $DBModCntxt);

            $User = $CrUsrDba->getUserInfoByLogin($adm_login);
            if($User){$User = new \app\models\UserMDL($User);}

            $CrUsrDba->activateSecurityAcc($User->getSecId());
            if(!$User->getIsAdmin()){
                $CrUsrDba->switchUserSuperAdminRights($User->getUserId());
            }
            include_once($InstallationSourcePath.'/steps/5/index.php');
        } else {
            include_once($InstallationSourcePath.'/steps/4/index.php');
        }
    break;

    case 5:
        if(CIS::l($_POST, 'add_demo', false)){
            include_once($InstallationSourcePath.'/inc/include_dbconstants.php');
            include_once($InstallationSourcePath.'/inc/include_dbas.php');

            FileDBA::createDirectoryWithRights(FileDBA::FILE_DIRECTORY());

            $UsrDba = new \app\dba\UserDBA();
            $DBModCntxt = new \app\dba\DBModificationContext(-1, NULL, $UsrDba, \app\dba\constants\DBChanges::auto);

            $RU_lang_id = 0; // according to previously created default *_languages table
            $EN_lang_id = 1; // according to previously created default *_languages table

            include_once($InstallationSourcePath.'/inc/demo/Alice.php');
            include_once($InstallationSourcePath.'/inc/demo/Lilly.php');
            include_once($InstallationSourcePath.'/inc/demo/Ted.php');

        }

        copy($InstallationSourcePath.'/sources/Application.php', $rootCrPath.'/Application.php');
        copy($InstallationSourcePath.'/sources/AppFilter.php', $rootCrPath.'/AppFilter.php');
        copy($InstallationSourcePath.'/sources/ModelProcessor.php', $rootCrPath.'/ModelProcessor.php');

        $display_path = $_SERVER['DOCUMENT_ROOT'].'/display';
        FileDBA::createDirectoryWithRights($display_path);

        FileDBA::copyRecursive($InstallationSourcePath.'/demo/display', $display_path);

        FileDBA::copyRecursive($InstallationSourcePath.'/sources/fabrics', $rootCrPath.'/fabrics');
        FileDBA::copyRecursive($InstallationSourcePath.'/utilities', $_SERVER['DOCUMENT_ROOT'].'/utilities');
        copy($InstallationSourcePath.'/sources/.htaccess', $_SERVER['DOCUMENT_ROOT'].'/.htaccess');
        copy($InstallationSourcePath.'/demo/Loader.php', $_SERVER['DOCUMENT_ROOT'].'/Loader.php');
        copy($InstallationSourcePath.'/demo/WebUI.php', $_SERVER['DOCUMENT_ROOT'].'/WebUI.php');
        FileDBA::replaceTmpltsInFile(
            $_SERVER['DOCUMENT_ROOT'].'/WebUI.php'
            , array('site_url' => $project_url)
            , true
        );

        copy($InstallationSourcePath.'/inc/Done.txt', $InstallationSourcePath.'/Done.txt');
        $InstallationIsFinished = true;
    break;
}
?>
<? if($InstallationIsFinished) {?>
<div class="registration">
    <h2>KORtx Bee<?=Application::GlobalTransliter(array('RU'=>' успешно установлен.', 'EN'=>' was successfully installed.'), $lang)?></h2>
    <p>
        <a href="/"><?=Application::GlobalTransliter(array('RU'=>'Добро пожаловать', 'EN'=>'Welcome'), $lang)?>!</a>
    </p>
</div>
<? } ?>
<br/><br/>
        <? if($project_url!==null) {?>
        <input type="text" name="project_url" value="<?=$project_url?>"/>
        <? } ?>
        <? if($db_host && $db_user && $db_pass && $db_type) {?>
        <input type="text" name="db_host" value="<?=$db_host?>"/>
        <input type="text" name="db_user" value="<?=$db_user?>"/>
        <input type="password" name="db_pass" value="<?=$db_pass?>"/>
        <input type="text" name="db_type" value="<?=$db_type?>"/>
        <? } ?>
        <? if($project_keyword!==null) {?>
        <input type="text" name="project_keyword" value="<?=$project_keyword?>"/>
        <? } ?>
        <? if($adm_login && $adm_pass) {?>
        <input type="text" name="adm_login" value="<?=$adm_login?>"/>
        <input type="password" name="adm_pass" value="<?=$adm_pass?>"/>
        <? } ?>
    </form>
</main>

<div class="footer-bottom__container">
    <div class="prize" style="vertical-align: middle; display: block;">
        <img src="/Install/display/img/KortX_Bee_logo.png" width="60" class="prize-img" style="float: left; margin-top: 6px;">
    </div>
    <div class="certificate" style="position: relative; left: 4px;">
        <b>KORtx&nbsp;Bee<i></i></b>
        <br/>
        2013<span style="font-size: 50%;">&nbsp;</span>-<div class="logo text infinum">&infin;</div>
    </div>
</div>

</BODY>