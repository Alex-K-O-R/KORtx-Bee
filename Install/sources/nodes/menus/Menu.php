<?php
namespace app\nodes\menus;

use app\pages\Page;
abstract class Menu {
    /**
     * @var Page $Page
     */
    protected $Page;
    public function __construct($Page){
        $this->Page = $Page;
        $this->Draw($this->FormModel());
    }

    /**
     * @return MenuRecModel[]
     */
    abstract function FormModel();

    /**
     * @param MenuRecModel[] $menu
     * @return mixed
     */
    abstract function Draw($menu);
}