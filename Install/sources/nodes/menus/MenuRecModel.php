<?php


namespace app\nodes\menus;


class MenuRecModel {
    public $name;
    public $href;
    public $is_inactive;
    public $is_selected;
    public $special_css_class;

    /**
     * @var MenuRecModel[] innerMenus
     */
    public $innerMenus;

    /*@param self $inner_items*/
    public function __construct($name, $href, $is_selected = false, $is_inactive = false, $special_css_class = ""){
        $this->name = $name;
        $this->href = $href;
        $this->special_css_class = $special_css_class;
        $this->is_inactive = $is_inactive;
        $this->is_selected = $is_selected;
    }

    /**
     * @param MenuRecModel[] $arrayOfSubmenuRecords
     */
    public function setInners(&$arrayOfSubmenuRecords){
        foreach($arrayOfSubmenuRecords as $rec){
            if($rec->is_selected) $this->is_selected = $rec->is_selected;
            break;
        }
        $this->innerMenus = $arrayOfSubmenuRecords;
    }
}