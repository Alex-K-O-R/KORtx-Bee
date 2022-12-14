<?php
namespace app\models;

use app\models\inner\DynamicsModelContainer;
use app\models\inner\_UserMDL;
use app\utilities\inner\DataConversion;
use app\dba\FileDBA;
use app\constants\DefaultImages;

/**
 * Class UserMDL
 * @package app\models
 */
class UserMDL extends _UserMDL {
    //user_info
    protected $d_general_info;
    protected $d_additional_info;
    protected $d_name;
    protected $d_surname;
    protected $d_profession;
    protected $avatar_path;
    protected $is_admin;


    // Dialect-depending field - a field/property that has different value for different language
    // This is links to the dialect-depending fields that are positions of the SQL row result
    // For better visibility dialect-depending properties above are marked with [d_*] prefixes
    private static $dynamicFieldsIndexes = array(5,6,7,8,9);

    use DynamicsModelContainer;

    function __construct(array &$row)
    {
        $j = 0;

        $this->user_id = &$row[$j++];
        $this->sec_id = &$row[$j++];
        $this->is_blocked = &$row[$j++];
        $this->last_selected_lang_id = &$row[$j++];
        $this->resource_hash = &$row[$j++];
        $this->d_general_info = &$row[$j++];
        $this->d_additional_info = &$row[$j++];
        $this->d_name = &$row[$j++];
        $this->d_surname = &$row[$j++];
        $this->d_profession = &$row[$j++];
        $this->avatar_path = &$row[$j++];
        $this->is_admin = &$row[$j++];

        $this->login = &$row[$j++];
        $this->activated = &$row[$j++];
        $this->add_date = &$row[$j++];
        $this->last_login_date = &$row[$j++];

        $this->is_blocked = DataConversion::DBBooleanConversion($this->is_blocked);
        $this->activated = DataConversion::DBBooleanConversion($this->activated);
        $this->is_admin = DataConversion::DBBooleanConversion($this->is_admin);
    }


    public function getAddDate($format = null)
    {
        return DataConversion::DATETIME_formatting($this->add_date, $format);
    }

    public function getLastLoginDate($format = null)
    {
        return DataConversion::DATETIME_formatting($this->last_login_date, $format);
    }

    public function getAvatarImageUrl(){
        $img = null;
        if($this->getAvatarPath()) {
            $img = (FileDBA::DIR_URL($this->getResourceHash()).$this->getAvatarPath());
        }
        if($img && is_file($_SERVER['DOCUMENT_ROOT'].$img))
            return $img;
        else return DefaultImages::UserAvatar;
    }

    public function getFullNameFioOrLogin($delimeter = ' ', $order = true)
    {
        return ($this->getDName())?
            ($order?($this->getDName().$delimeter.$this->getDSurname()):($this->getDSurname().$delimeter.$this->getDName())):
            $this->getLogin();
    }

    public function getActivated()
    {
        return $this->activated;
    }

    public function getAvatarPath()
    {
        return $this->avatar_path;
    }

    public function getDAdditionalInfo()
    {
        return $this->d_additional_info;
    }

    public function getDGeneralInfo()
    {
        return $this->d_general_info;
    }

    public function getDName()
    {
        return $this->d_name;
    }

    public function getDProfession()
    {
        return $this->d_profession;
    }

    public function getDSurname()
    {
        return $this->d_surname;
    }

    public static function getDynamicFieldsIndexes()
    {
        return self::$dynamicFieldsIndexes;
    }

    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    public function getIsBlocked()
    {
        return $this->is_blocked;
    }

    public function getLastSelectedLangId()
    {
        return $this->last_selected_lang_id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getResourceHash()
    {
        return $this->resource_hash;
    }

    public function getSecId()
    {
        return $this->sec_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }



}