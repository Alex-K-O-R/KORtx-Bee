<?php
namespace app\pages;

use app\Application;
use app\utilities\inner\Array_;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class PageProps {
    private $title;
    private $description;
    private $keywords;
    private $h1;
    private $icon;
    private $metric;


    private $state;

    private $vars;
    /*
        function __construct()
        {

        }
    */

    public function setProps($h1, $title = null, $keywords = null, $description = null, $icon = null, $metric = null){
        if (CIE::l($title)) $this->title = $title;
        if (CIE::l($description)) $this->description = $description;
        if (CIE::l($keywords)) $this->keywords = $keywords;
        if (CIE::l($h1)) $this->h1 = $h1;
        if (CIE::l($icon)) $this->icon = $icon;
        if (CIE::l($metric)) $this->metric = $metric;
        return $this;
    }





    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

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
        if($vars == null){return;}
        if(!CIS::l($this->vars)){$this->vars = array();}
        if(!is_array($vars)){return;/*$vars = array($vars);*/}
        $this->vars = array_merge($this->vars, $vars);
    }

    public function addVar($key, $var)
    {
        if($var == null || !CIE::l($key)){return;}
        if(!CIS::l($this->vars)){$this->vars = array();}
        $this->vars[$key] = $var;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return null
     */
    public function getH1()
    {
        return $this->h1;
    }

    /**
     * @return null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return null
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @return null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title?$this->title:$this->h1;
    }

    /**
     * @param null $ind
     * @return mixed
     */
    public function getVar($ind = null)
    {
        //if($this->vars === null) {return array();}
        return (CIS::l($ind))?CIS::l($this->vars, $ind, false):$this->vars;
    }

    /** Метод возвращает переменную, если переменная не массив, пакует её в массив длины 1
     * @param null $ind
     * @return mixed
     */
    public function getVarAsArray($ind = null)
    {
        return Array_::varToArray($this->getVar($ind));
    }
}