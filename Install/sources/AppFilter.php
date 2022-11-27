<?php
namespace app;


use app\models\inner\LanguageMDL;
use app\utilities\inner\CIE;

class AppFilter {
    private $startDate;
    private $endDate;

    private $selectedSegments;

    private $portalLanguageInfo;

    /**
     * @param null $startDate
     * @param null $endDate
     * @param null $selectedSegments
     * @param LanguageMDL $useLanguage
     */
    function __construct($startDate = null, $endDate = null, $selectedSegments  = null, $useLanguage  = null)
    {
        $this->startDate = (CIE::l($startDate))?date('Y-m-d', strtotime($startDate)):date('Y-m-d',strtotime('-2 weeks'));
        $this->endDate = (CIE::l($endDate))?date('Y-m-d H:i:s', strtotime($endDate)):date('Y-m-d H:i:s');
        $this->selectedSegments = $selectedSegments;
        //Fuck this next string???
        $this->portalLanguageInfo = $useLanguage;
    }

    /**
     * @param mixed $beginDate
     */
    public function setStartDate($beginDate)
    {
        $this->startDate = $beginDate;
    }

    /**
     * @param null $frmt
     * @return bool|string
     */
    public function getStartDate($frmt = null)
    {
        if (isset($frmt)) return date($frmt, strtotime($this->startDate));
        else return $this->startDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @param null $frmt
     * @return bool|string
     */
    public function getEndDate($frmt = null)
    {
        if (isset($frmt)) return date($frmt, strtotime($this->endDate));
        else return $this->endDate;
    }

    /**
     * @param mixed $selectedSegments
     */
    public function setSelectedSegments($selectedSegments)
    {
        $this->selectedSegments = $selectedSegments;
    }

    /**
     * @return mixed
     */
    public function getSelectedSegments()
    {
        return (!empty($this->selectedSegments))?$this->selectedSegments:array();
    }

    /**
     * @return LanguageMDL
     */
    public function getPortalLanguageInfo()
    {
        return $this->portalLanguageInfo;
    }

}