<?
use app\WebUIApplication;
use app\pages\Pages;
use app\pages\Theme;
use app\nodes\menus\LanguageMenu;
use app\nodes\menus\TopGeneralMenu;

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
                <span>
                    <a href="<?=Pages::logout?>">
                        <?=$App->Translater(array('RU'=>'Выход', 'EN'=>'Logout'));?>
                    </a>
                </span>
            </div>
        </div>
        <div class="static">
            <div class="outset">
                <div class="header-lang-selector header-icon">
                    <?
                        LanguageMenu\LanguageMenu::Draw($App, LanguageMenu\LanguageMenuOptions::buildFromParameters(
                            $App->getSupportedLanguageInfo()
                        ));
                    ?>
                </div>
                <?
                    TopGeneralMenu\TopGeneralMenu::Draw($App, TopGeneralMenu\TopGeneralMenuOptions::buildFromParameters(
                        $App
                    ));
                ?>
            </div>
        </div>
        <div class="static">
            <div class="outset body">