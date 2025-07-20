<?php
namespace app\display\nodes\menu;
use app\WebUIApplication;
use app\utilities\inner\menus\MenuRecModel;

use app\pages\Pages;

class TopMenuGen extends TopMenuTMPLT {
    public function FormModel(){
        $name = array('RU'=>'Главная', 'EN'=>'Main page');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::main), ($this->Page->getPageMask()==Pages::main)
        );

        $name = array('RU'=>'О проекте', 'EN'=>'About');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::about), ($this->Page->getPageMask()==Pages::about)
        );

        $name = array('RU'=>'Участники', 'EN'=>'Participants');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_list), ($this->Page->getPageMask()==Pages::user_list) || ($this->Page->getPageMask()==Pages::user_info)
        );

        $name = array('RU'=>'Личный кабинет', 'EN'=>'Personal block');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::login), ($this->Page->getPageMask()==Pages::login)
        );

        return $arr;
    }
}