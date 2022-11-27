<?php
namespace app\models\inner;


class LanguageMDL {
    private $lang_id;
    private $acronym;
    private $fullname;
    private $phpDateMask;
    private $jsDateMask;

    function __construct($lang_id, $acronym, $fullname, $phpDateMask, $jsDateMask)
    {
        $this->acronym = $acronym;
        $this->fullname = $fullname;
        $this->lang_id = $lang_id;
        $this->phpDateMask = $phpDateMask;
        $this->jsDateMask = $jsDateMask;
    }

    public function getAcronym()
    {
        return $this->acronym;
    }

    public function getFullname()
    {
        return $this->fullname;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function getPhpDateMask()
    {
        return $this->phpDateMask;
    }

    public function getJsDateMask()
    {
        return $this->jsDateMask;
    }

}