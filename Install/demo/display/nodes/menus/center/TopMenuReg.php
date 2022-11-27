<?php
namespace app\display\nodes\menu;
use app\WebUIApplication;
use app\utilities\inner\menus\MenuRecModel;
use app\pages\Pages;

class TopMenuReg extends TopMenuTMPLT {
    public function FormModel(){
        /**
         * @var $App WebUIApplication
         */
        $App = $this->Page->Application();
        
        $name = array('RU'=>'О себе', 'EN'=>'Personal info');
        $arr[] = new MenuRecModel(
            $name
            , WebUIApplication::domain.Pages::getDynamic(Pages::personal_cabinet)
            , ($this->Page->getPageMask()==Pages::personal_cabinet)
        );

        $name = array('RU'=>'О проекте', 'EN'=>'About');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::about), ($this->Page->getPageMask()==Pages::about)
        );

        $name = array('RU'=>'Участники', 'EN'=>'Participants');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_list), ($this->Page->getPageMask()==Pages::user_list) || ($this->Page->getPageMask()==Pages::user_info)
        );

        if($App->getUserInfo() && $App->getUserInfo()->getIsAdmin()){
            $name = array('RU'=>'Управление пользователями', 'EN'=>'User control');
            $arr[] = new MenuRecModel(
                $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_control), ($this->Page->getPageMask()==Pages::user_control), null
            );

            $name = array('RU'=>'Доступ к БД', 'EN'=>'Database access');
            $arr[] = new MenuRecModel(
                $name, WebUIApplication::domain.Pages::getDynamic(Pages::db_client), ($this->Page->getPageMask()==Pages::db_client), null
            );
        }

        $name = array('RU'=>'Главная', 'EN'=>'Main page');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::main), ($this->Page->getPageMask()==Pages::main), null
        );

        $name = array('RU'=>'Выход', 'EN'=>'Logout');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::logout), ($this->Page->getPageMask()==Pages::logout), null
        );

        return $arr;
    }
}