<?php
namespace app\nodes\menus\TopGeneralMenu;
use app\nodes\menus\MenuRecModel;
use app\WebUIApplication;
use app\pages\Pages;

class Registered {
    public static function buildSections(WebUIApplication $App){
        $name = array('RU'=>'О себе', 'EN'=>'Personal info');
        $arr[] = new MenuRecModel(
            $name
            , WebUIApplication::domain.Pages::getDynamic(Pages::personal_cabinet)
            , ($App->Page()->getPageMask()==Pages::personal_cabinet)
        );

        $name = array('RU'=>'О проекте', 'EN'=>'About');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::about), ($App->Page()->getPageMask()==Pages::about)
        );

        $name = array('RU'=>'Участники', 'EN'=>'Participants');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_list), ($App->Page()->getPageMask()==Pages::user_list) || ($App->Page()->getPageMask()==Pages::user_info)
        );

        if($App->getUserInfo() && $App->getUserInfo()->getIsAdmin()){
            $name = array('RU'=>'Управление пользователями', 'EN'=>'User control');
            $arr[] = new MenuRecModel(
                $name, WebUIApplication::domain.Pages::getDynamic(Pages::user_control), ($App->Page()->getPageMask()==Pages::user_control), null
            );

            $name = array('RU'=>'Доступ к БД', 'EN'=>'Database access');
            $arr[] = new MenuRecModel(
                $name, WebUIApplication::domain.Pages::getDynamic(Pages::db_client), ($App->Page()->getPageMask()==Pages::db_client), null
            );
        }

        $name = array('RU'=>'Главная', 'EN'=>'Main page');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::main), ($App->Page()->getPageMask()==Pages::main), null
        );

        $name = array('RU'=>'Выход', 'EN'=>'Logout');
        $arr[] = new MenuRecModel(
            $name, WebUIApplication::domain.Pages::getDynamic(Pages::logout), ($App->Page()->getPageMask()==Pages::logout), null
        );

        return $arr;
    }
}