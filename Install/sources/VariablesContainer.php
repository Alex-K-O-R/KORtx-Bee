<?php

namespace app;

use app\utilities\inner\Arrays;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

trait VariablesContainer
{
    private $vars;

    /**
     * @param mixed $vars
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    /**
     * @param mixed $vars
     */
    public function addVars($vars)
    {
        if(!CIS::l($this->vars)){$this->vars = array();}
        if(!is_array($vars)){return;/*$vars = array($vars);*/}
        $this->vars = array_merge($this->vars, $vars);
    }

    public function addVar($key, $var)
    {
        if(!CIE::l($key)){return;}
        if(!CIS::l($this->vars)){$this->vars = array();}
        $this->vars[$key] = $var;
    }

    /**
     * @param null $ind
     * @return mixed
     */
    public function getVar($ind = null)
    {
        return (CIS::l($ind))?CIS::l($this->vars, $ind, false):$this->vars;
    }

    /** Метод возвращает переменную, если переменная не массив, пакует её в массив длины 1
     * @param null $ind
     * @return mixed
     */
    public function getVarAsArray($ind = null)
    {
        return Arrays::varToArray($this->getVar($ind));
    }
}