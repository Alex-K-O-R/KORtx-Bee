<?php
namespace app\dba;
use Image;
use app\models\AccountMDL;
use app\utilities\inner\CIS;
use app\utilities\inner\MathEtc;

class FileDBA {//TODO: WHEN UPLOADED WITH SAME NAME, SAVE OLD
    const UPLOAD_METHOD_Link = 'link';
    const UPLOAD_METHOD_Blob = 'blob';

    const COMMON_ERRORS_FILES_TOO_BIG = 1;
    private $filesPath;
    private $accPath;

    protected static function BASE_DIRECTORY(){
        return '/storage/';
    }

    static function FILE_URL($section = null)
    {
        return self::GLOBAL_getResourceRelativePath($section);
    }

    static function FILE_DIRECTORY($section = null)
    {
        return $_SERVER['DOCUMENT_ROOT'].self::GLOBAL_getResourceRelativePath($section);
    }

    public static function GLOBAL_getResourceRelativePath($section){
        return self::BASE_DIRECTORY().(($section===null)?'':$section.'/');
    }


    function __construct($resourceFolder)
    {
        $this->accPath = trim($resourceFolder, '\/');
        $this->filesPath = self::FILE_DIRECTORY($this->accPath);
        self::createDirectoryWithRights($this->filesPath, 0766);
    }


    /** Creates directory with random hash name
     * @param $salt
     * @param int $length - directory name's length
     * @return array|null|string
     */
    public static function createHashForDirectory($salt, $length = 7){
        do {
            $salt = MathEtc::generateRandomScrtCnsqnc($salt, $length);
        } while(is_dir(self::FILE_DIRECTORY().$salt));

        mkdir(self::FILE_DIRECTORY().$salt, 0766);

        if(is_dir(self::FILE_DIRECTORY().$salt)) {return $salt;}

        return null;
    }


    public static function removeDirectory($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        self::removeDirectory($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            rmdir($dir);
        }
    }

    public static function copyRecursive($src_dir, $dest) {
        if(!is_dir($dest)){
            self::createDirectoryWithRights($dest);
            if(!is_dir($dest)){return false;}
        }
        if (is_dir($src_dir)) {
            $objects = scandir($src_dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($src_dir."/".$object))
                        self::copyRecursive($src_dir."/".$object, $dest."/".$object);
                    else
                        copy($src_dir."/".$object, $dest."/".$object);
                }
            }
            //rmdir($src_dir);
        }
    }


    function recursive_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recursive_copy($src .'/'. $file, $dst .'/'. $file);
                }
                else {
                    copy($src .'/'. $file,$dst .'/'. $file);
                }
            }
        }
        closedir($dir);
    }


    /**
     * @param $filePthNm
     * @param bool $removeParentDirectoryIfEmpty
     */
    public static function removeFile($filePthNm, $removeParentDirectoryIfEmpty = false) {
        if (is_file($filePthNm)) {
            $object = pathinfo($filePthNm, PATHINFO_DIRNAME);
            unlink($filePthNm);
            if ($removeParentDirectoryIfEmpty && !($files = glob($object . "/*"))) {
                self::removeDirectory($object);
            }
        }
    }


    /** Changes permissions for directory
     * @param $dir
     * @param $dirPermissions
     * @param $filePermissions
     */
    private static function chmod_r($dir, $dirPermissions, $filePermissions) {
        $dp = opendir($dir);
        while($file = readdir($dp)) {
            if (($file == ".") || ($file == ".."))
                continue;

            $fullPath = $dir."/".$file;

            if(is_dir($fullPath)) {
                chmod($fullPath, $dirPermissions);
                self::chmod_r($fullPath, $dirPermissions, $filePermissions);
            } else {
                chmod($fullPath, $filePermissions);
            }

        }
        closedir($dp);
    }


    /** Returns filename with number in suffix if file's already existing in directory given
     * @param $path
     * @param $fileName
     * @return string
     */
    public static function renameDuplicates($path, $fileName)
    {
        $divider_of_copy_index = '_';

        $fileExtension = mb_substr($fileName, strrpos($fileName, '.'));
        $fileName = mb_substr($fileName, 0, strrpos($fileName, '.'));

        $returnValue = $fileName . $fileExtension;

        $copy = 1;
        while (file_exists($path . $returnValue)) {
            $returnValue = $fileName . $divider_of_copy_index . $copy . $fileExtension;
            $copy++;
        }
        return $returnValue;
    }


    public static function replaceTmpltsInFile($file, $templateKtoReplaceVArr, $overwrite = false){
        $object = file_get_contents($file);
        if (!$object) {
            return null;
        }

        foreach ($templateKtoReplaceVArr as $k => $v) {
            $object = str_replace('{'.$k.'}', $v, $object);
        }

        $resLink = $file;

        if(!$overwrite){
            $path = dirname($file);
            $resLink = basename($file);
            $resLink = $path.self::renameDuplicates($path, $resLink);
        }

        file_put_contents($resLink, $object);



        /* Nerdy..
        if ((file_exists($this->getFileFolder() . $file))
            and (is_readable($this->getFileFolder() . $file))
                and ($file != '')
        ) {
        */
    }



    public static function getFilesPathsListOfTypeInDirectory($dir, $type='*') {
        $result = array();
        $fileList = glob($dir.'/'.$type);
        foreach($fileList as $filename){
            if(is_file($filename)){
                $result[] = $filename;
            }
        }
        return $result;
    }


    public static function createDirectoryWithRights($dir, $rights = 0776){
        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir, $rights, true);
        }
        if(!is_writable($dir)){
            chmod($dir, $rights);
        }
        self::chmod_r($dir, $rights, $rights);
    }


    public static function getExtension($filename, $files_only = true) {
        if(($files_only&&!mb_stristr($filename, '.'))||mb_strlen(str_replace('.','',$filename))===0){return null;}
        $filename=strtolower(substr(strrchr($filename, '.'), 1));
        if ($filename=='jpeg') {$filename='jpg';};
        return $filename;
    }


// Remove anything which isn't a word, whitespace, number// or any of the following caracters -_~,;[]().// If you don't need to handle multi-byte characters// you can use preg_replace rather than mb_ereg_replace// Thanks @Łukasz Rysiak!

    private static function stringEngToProperFilename($string) {
        $string = mb_strtolower($string);
        $string = str_replace ("Ã¸", "oe", $string);
        $string = str_replace ("Ã¥", "aa", $string);
        $string = str_replace ("Ã¦", "ae", $string);
        $string = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])",'.'/*was ''*/, $string);// Remove any runs of periods (thanks falstro!)
        $string = mb_ereg_replace("([\.]{2,})",'.'/*was ''*/, $string);
        $string = str_replace (" ", "_", $string);

        //$string = mb_ereg_replace ("/[^0-9^a-z^_^.]/", "", $string);
        return $string;
    }

}