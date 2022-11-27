<?php
namespace app\filters;


class ModelFilterDescription {
    private $GETFieldName;
    private $isSQLFieldDynamic;
    private $filterType;
    private $filterValue;

    function __construct($GETFieldName, $isSQLFieldDynamic, $filterType = FilterModes::CONTAINS, $filterValue = null)
    {
        $this->GETFieldName = $GETFieldName;
        $this->isSQLFieldDynamic = $isSQLFieldDynamic;
        $this->filterType =  $filterType;
        $this->filterValue = $filterValue;
    }

    public function getGETFieldName()
    {
        return $this->GETFieldName;
    }

    public function getFilterType()
    {
        return $this->filterType;
    }

    public function getFilterValue()
    {
        return $this->filterValue;
    }

    public function getIsSQLFieldDynamic()
    {
        return $this->isSQLFieldDynamic;
    }

    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;
    }

    public function setFilterValue($filterValue)
    {
        $this->filterValue = $filterValue;
    }
}
?>