<?php
namespace app\pages;

class ThemeRuler {
    /**
     * @var $themes Theme[]
     */
    private $themes = array();
    private $selectedTheme = 0;

    public function ThemeRuler($theme = null){
        if ($theme) array_push($this->themes, $theme);
    }

    /**
     * @param $ThemeArr Theme[]
     */
    public function addThemes($ThemeArr){
        foreach($ThemeArr as &$thm){
            if ($thm) array_push($themes, $thm);
        }
    }

    /**
     * @param $Theme Theme
     */
    public function addTheme($Theme){
        if($Theme){
            array_push($this->themes, $Theme);
            $this->selectedTheme = count($this->themes)-1;
        }
    }

    /**
     * @param $name
     * @return Theme
     */
    public function getThemeByName($name){
        $i=0;
        //var_dump($this->themes);
        foreach($this->themes as &$thm) if($thm->getName()==$name) {$this->selectedTheme=$i; return $thm;} else $i++;
        return null;
    }

    /**
     * @return Theme
     */
    public function Get($context){
        return $this->themes[$this->selectedTheme]->setContext($context);
    }
}
