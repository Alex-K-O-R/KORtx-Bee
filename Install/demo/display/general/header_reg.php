<?
use app\WebUIApplication;
use app\display\nodes\menu\LanguageMenu;
use app\display\nodes\menu\TopMenuReg;
use app\pages\Pages;
use app\pages\Theme;

/**
 * @var $this Theme
 */
/**
 * @var $App WebUIApplication
 */
$App = $this->Page()->Application();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="max-age=604800">

    <title><?=$this->Page()->Properties()->getTitle()?></title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="icon" href="/Install/demo/display/img/brand/favicon.ico">

    <?=$this->getCss()?>
    <?=$this->getJs()?>
</head>
<body>
<div class="wrapper">
    <div class="div-block-21">
        <div class="header">
            <div class="logo">
                <img src="/Install/demo/display/img/brand/logo.png" alt="Logo" />
                <a href="<?=Pages::main?>">
                <div>
                    <span>T-demo.Website</span>
                </div>
                </a>
            </div>
            <div class="login" style="top: 0.6vh;right: 1.5vh;">
                <span><a href="<?=Pages::personal_cabinet?>"><?=$App->getUserInfo()->getLogin()?></a>
                </span>
            </div>
            <div class="login" style="/*! top: 6vh; */bottom: 1vh; right: 1.5vh;">
                <img src="/Install/demo/display/img/icons/login.png">
                <span><a href="<?=Pages::logout?>">Выход</a></span>
            </div>
        </div>
        <div class="static">
            <div class="outset">
                <div class="header-lang-selector header-icon">
                    <?
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/menus/LanguageMenu.php';
                    LanguageMenu::Draw($App, $App->getSupportedLanguageInfo());
                    ?>
                </div>
                <?
                require_once $_SERVER['DOCUMENT_ROOT'].'/display/nodes/menus/center/TopMenuTMPLT.php';
                require_once $_SERVER['DOCUMENT_ROOT'].'/display/nodes/menus/center/TopMenuReg.php';
                new TopMenuReg($this->Page());
                ?>
            </div>
        </div>
        <div class="static">
            <div class="outset body">