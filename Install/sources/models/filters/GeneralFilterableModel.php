<?php
namespace app\filters\models;

use app\dba\DBAccess;
use app\dba\DBAccessGeneric;
use app\filters\FilterConverter;
use app\filters\FilterModes;
use app\filters\FilterToDbOperatorConverter;
use app\filters\ModelFilterDescription;
use app\utilities\inner\Array_;
use app\utilities\inner\CIS;
use app\utilities\inner\DataConversion;


abstract class GeneralFilterableModel {
    /**
     * @var ModelFilterDescription[]
     */
    private $filterDescriptions = array();

    private $lang_id = null;

    private $resultSQL = '';

    private $filter = array();

    private $DBA = null;

    protected abstract function LoadFilterFields();

    public function __construct($get, $lang_id = null){
        $this->filterDescriptions = $this->LoadFilterFields();
        $this->lang_id = ($lang_id===null)?$lang_id:intval($lang_id);
        //$all_incomings = $this->getAllAvailableFieldsForFiltration();

        $this->processLevel($get, $this->filterDescriptions);
    }

    public function EscapeValues($Provider){
        $this->DBA = $Provider;
        return $this;
    }

    /**
     * @param $get
     * @param $all_incomings
     */
    private function processLevel($get, $all_incomings)
    {
        $count=0;
        foreach($all_incomings as $i => $v) {
            //if($count==0) $this->resultSQL .= ' AND (';
            if (is_array($v)) {
                if($count>0)
                    $this->resultSQL .= ' AND ';
                $this->resultSQL .= ' (';
                $this->processLevel($get, $v);
                //if($count>0)
                $this->resultSQL .= ') ';
            } else {
                /**
                 * @var ModelFilterDescription $v
                 */
                if($count>0) $this->resultSQL .= ' OR ';


                $values = array();
                if(mb_stristr($v->getGETFieldName(),',') && $key = explode(',', $v->getGETFieldName())){
                    foreach($key as $k){
                        $k = trim($k);
                        $values[] = CIS::l($get, $k, null);
                        $this->filter[$k] = CIS::l($get, $k, '');
                    }
                } else {
                    if($v->getGETFieldName()) $this->filter[$v->getGETFieldName()] = CIS::l($get, $v->getGETFieldName(), '');
                    $values = CIS::l($get, $v->getGETFieldName(), null);
                }

                if (count($values) || $v->getFilterValue()) $this->processElement($values, $i, $v);
                else $this->resultSQL .= ' 1=1 ';
                /*TODO 0611: if($count==0) $this->resultSQL .= ' (';
                if($count==count($values))$this->resultSQL .= ') ';*/
                //$count++;
            }
            $count++;
        }
    }

    /**
     * @param $get
     * @param ModelFilterDescription $element
     * @param $key
     */
    private function processElement($val, $key, &$element)
    {
        //$this->filterDescriptions[$element]->setFilterType($filterMode::CONTAINS); //TODO: Auto-selection depending on get with suffix
        if(!$element->getFilterValue()) $element->setFilterValue(CIS::l($val, null, null)); //TODO: Возможно, следует ввести для фильтров суффиксы в принципе

        /*        if($element->getIsSQLFieldDynamic()){
                    $this->resultSQL .= FilterToDbOperatorConverter::SQLConditionStructureForInput(
                        $element->getSQLFieldName(),
                        $element->getFilterType(),//'OF',
                        $element->getFilterValue(),
                        //$this->lang_id
                    );
                    print_r($element); exit;
                } else {*/
        $this->resultSQL .= FilterConverter::SQLConditionStructureForInput(
            $key,/*TODO 0611*/
            $element->getFilterType(),//'OF'
            ($this->DBA)?$this->DBA->escape_string($element->getFilterValue()):$element->getFilterValue(),
            $element->getIsSQLFieldDynamic()?true:null
        );
        /*}*/
    }

    public function getAllAvailableFieldsForFiltration(){
        return count($this->filterDescriptions)?array_keys($this->filterDescriptions):$this->filterDescriptions;
    }


    public function getResultSQLConditions()
    {
        return ($this->resultSQL==='')?'':' AND ('.$this->resultSQL.')';
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getJsFilterRepresentation(){
        ?>{
        <?foreach($this->getFilter() as $n=>$f){?>
            '<?=$n?>':'<?=DataConversion::TEXT_to_JSON($f)?>',
        <?}?>
        }<?
    }

}

?>