<?php
namespace app\nodes\menus;

use app\nodes\Node;
use app\nodes\NodeOptions;
use app\pages\Page;

abstract class GenericMenu extends Node {

    /**
     * @var Page $Page
     */
    protected $Page;

    protected abstract function Render(?NodeOptions $nodeParametersContainer = null): void;
}