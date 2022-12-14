<?php
namespace app;

class Loader {
    public static function load_php_recursive($directory) {
        if(is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            foreach($scan as $file) {
                if(is_dir($directory."/".$file)) {
                    self::load_php_recursive($directory."/".$file);
                } else {
                    if(strpos($file, '.php') !== false) {
                        include_once($directory."/".$file);
                    }
                }
            }
        }
    }
}

// Классы доступа к БД и модели
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/dba/DBConnProvider.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/dba/DBAccessGeneric.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/dba/FilterToDbOperatorConverter.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/dba/FilterConverter.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/models/inner/SecurityMDL.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/dba/DBALoader.php');

// Системные константы
Loader::load_php_recursive($_SERVER['DOCUMENT_ROOT'].'/constants');

//Утилиты
Loader::load_php_recursive($_SERVER['DOCUMENT_ROOT'].'/core/_core/utilities');
Loader::load_php_recursive($_SERVER['DOCUMENT_ROOT'].'/core/utilities');

// Классы визуализатора;
include_once($_SERVER['DOCUMENT_ROOT'].'/core/fabrics/PageThemes.php');
Loader::load_php_recursive($_SERVER['DOCUMENT_ROOT'].'/core/_core/fabrics');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/fabrics/Pages.php');

// Классы приложения
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/AppFilter.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/Application.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/core/_core/ModelProcessor.php');
Loader::load_php_recursive($_SERVER['DOCUMENT_ROOT'].'/core/');
include_once($_SERVER['DOCUMENT_ROOT'].'/WebUI.php');
?>