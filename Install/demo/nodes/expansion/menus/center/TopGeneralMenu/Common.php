<?php
namespace app\nodes\menus\TopGeneralMenu;
use app\nodes\menus\MenuRecModel;
use app\WebUIApplication;

use app\pages\Pages;

class Common {
    public static function buildSections(WebUIApplication $App){
        $name = array('RU'=>'Главная', 'EN'=>'Main page');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::main), ($App->Page()->getPageMask()==Pages::main)
        );

        $name = array('RU'=>'О проекте', 'EN'=>'About');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::about), ($App->Page()->getPageMask()==Pages::about)
        );

        $name = array('RU'=>'Участники', 'EN'=>'Participants');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_list), ($App->Page()->getPageMask()==Pages::user_list) || ($App->Page()->getPageMask()==Pages::user_info)
        );

        $name = array('RU'=>'Личный кабинет', 'EN'=>'Personal block');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::login), ($App->Page()->getPageMask()==Pages::login)
        );

        return $arr;
    }
}