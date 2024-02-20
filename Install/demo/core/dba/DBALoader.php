<?php
namespace app\dba;

trait LoadDbas {
    private $userDBA;
    /**
     * @return UserDBA
     */
    public function getUserDBA()
    {
        if(!$this->userDBA){
            $this->userDBA = new UserDBA();
        }
        return $this->userDBA;
    }

    private $WebPortalDBA;
    /**
     * @return WebPortalDBA
     */
    public function getWebPortalDBA()
    {
        if(!$this->WebPortalDBA){$this->WebPortalDBA = new WebPortalDBA();}
        return $this->WebPortalDBA;
    }
}