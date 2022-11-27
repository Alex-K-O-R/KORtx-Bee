<?php
namespace app\models\inner;

use app\ModelProcessor;
use com\db\FileDBA;
use utility\DataConversion;
use DefaultImages;

class _UserMDL extends SecurityMDL {
    //user_info
    protected $user_id;
    protected $sec_id;
    protected $is_blocked;
    protected $last_selected_lang_id;

    function __construct(array &$row)
    {
        $j = 0;

        $this->user_id = &$row[$j++];
        $this->sec_id = &$row[$j++];
        $this->is_blocked = &$row[$j++];
        $this->last_selected_lang_id = &$row[$j++];
        $this->login = &$row[$j++];
        $this->activated = &$row[$j++];
        $this->add_date = &$row[$j++];
        $this->last_login_date = &$row[$j++];
    }

    public function getActivated()
    {
        return $this->activated;
    }

    public function getAddDate()
    {
        return $this->add_date;
    }

    public function getIsBlocked()
    {
        return $this->is_blocked;
    }

    public function getLastLoginDate()
    {
        return $this->last_login_date;
    }

    public function getLastSelectedLangId()
    {
        return $this->last_selected_lang_id;
    }

    public function getLogin()
    {
        return $this->login;
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
