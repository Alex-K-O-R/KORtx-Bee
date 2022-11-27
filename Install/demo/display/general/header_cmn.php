<?
use app\display\nodes\menu\LanguageMenu;
use app\display\nodes\menu\TopMenuGen;
use app\pages\Pages;

/**
 * @var $this Page
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
            <div class="login">
                <img src="/Install/demo/display/img/icons/login.png"/>
                <span><a href="<?=Pages::login?>"><?=$App->Translater(array('RU'=>'Вход', 'EN'=>'Login'))?></a></span>
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
                require_once $_SERVER['DOCUMENT_ROOT'].'/display/nodes/menus/center/TopMenuGen.php';
                new TopMenuGen($this->Page());
                ?>
            </div>
        </div>
        <div class="static">
            <div class="outset body">