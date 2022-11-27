<?php
namespace app\dba;


class Page {
    const ORDER_DIRECTION_Ascending = ' ASC ';
    const ORDER_DIRECTION_Descending = ' DESC ';

    private $pageNumber = 1;
    private $pageSize = 10;
    private $orderByField = '';
    private $orderDirection = '';

    public function __construct($orderByField, $pageNum = null, $pageSize = null, $orderDirection = null){
        if ($pageNum>0) $this->pageNumber = $pageNum;
        if ($pageSize>0) $this->pageSize = $pageSize;
        if (isset($orderByField)) $this->orderByField = $orderByField;
        if (isset($orderDirection)) $this->orderDirection = $orderDirection;
    }

    /**
     * @return string
     */
    public function getOrderByField()
    {
        return $this->orderByField;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

}