<?php
namespace app;

class Loader {
    /**
     * @var array
     */
    private static $loadedList = [];

    /**
     * @return array
     */
    public static function getLoadedList(): array
    {
        return static::$loadedList;
    }

    public static function load($directory, $extension = '.php', $priorityPregMatchMasks = ['/(.*)original(.*)/', '/(.*)expansion(.*)/']) {
        static::$loadedList = [];

        static::load_recursive($directory, $extension);

        if(!empty($priorityPregMatchMasks)) {
            static::orderByMasks($priorityPregMatchMasks);
        }

        foreach (static::$loadedList as $filePath) {
            $extension = mb_substr($filePath, strrpos($filePath, '.'));
            if ($extension === '.php') {
                require_once($filePath);
            }
        }
    }

    private static function load_recursive($directory, $extension = '.php') {
        if(is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..

            foreach($scan as $file) {
                if(is_dir($directory."/".$file)) {
                    static::load_recursive($directory."/".$file, $extension);
                } else {
                    if(strpos($file, $extension) !== false) {
                        $filePath = $directory."/".$file;
                        static::$loadedList[] = $filePath;
                    }
                }
            }
        }
    }

    /**
     * @param $priorityPregMatchMasks
     * @return void
     */
    private static function orderByMasks($priorityPregMatchMasks): void
    {
        $orderedArr = [];
        $count = count($priorityPregMatchMasks);

        for ($i = 0; $i < $count; $i++) {
            foreach (static::$loadedList as $k => $file) {
                if (preg_match($priorityPregMatchMasks[$i], $file)) {
                    $orderedArr[] = $file;
                    unset(static::$loadedList[$k]);
                }
            }
        }

        static::$loadedList = array_merge($orderedArr, array_values(static::$loadedList));
    }
}


// Классы доступа к БД и модели
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/dba/DBConnProvider.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/dba/DBAccessGeneric.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/dba/FilterToDbOperatorConverter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/dba/FilterConverter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/models/inner/SecurityMDL.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/expansion/dba/DBALoader.php');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/original/dba');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/original/models');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/expansion/dba');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/expansion/models');

// Системные константы
Loader::load($_SERVER['DOCUMENT_ROOT'].'/constants');

//Утилиты
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/original/utilities');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/expansion/utilities');

// Классы визуализатора;
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/original/fabrics');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/expansion/fabrics/PageThemes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/expansion/fabrics/Pages.php');

// Классы приложения
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/AppFilter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/Application.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/original/ModelProcessor.php');
Loader::load($_SERVER['DOCUMENT_ROOT'].'/core/expansion/');
require_once($_SERVER['DOCUMENT_ROOT'].'/WebUI.php');

Loader::load($_SERVER['DOCUMENT_ROOT'].'/nodes', '.php', ['/(.*)original(.*)/','/(.*)TMPLT(.*)/', '/Menu(.*)/','/(.*)expansion(.*)/']);
?>