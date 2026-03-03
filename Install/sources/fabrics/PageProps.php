<?php
namespace app\pages;

use app\utilities\inner\CIE;
use app\VariablesContainer;

class PageProps {
    private $title;
    private $description;
    private $keywords;
    private $h1;
    private $icon;
    private $metric;


    private $state;

    use VariablesContainer;

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
}