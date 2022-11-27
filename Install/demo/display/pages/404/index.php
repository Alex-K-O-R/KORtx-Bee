<?
use app\WebUIApplication;
use app\pages\Page;
use app\pages\Pages;
/**
 * @var Page $this
 * @var WebUIApplication $App
 */
$App = $this->Application();
?>
<div class="content-wrapper">
    <div class="nav__tr">
        <ul class="tab-menu breadcrumbs">
            <li><a href="<?=Pages::main?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Home'))?></a></li>
            <li>404 &mdash; <?=$App->Translater(array('RU'=>'Страница не найдена', 'EN'=>'Page not found'))?>.</li>
        </ul>
    </div>
    <br/>
    <div class="body">
        <div class="page-404">
            <div><span>4</span><?=$App->Translater(array('RU'=>'етыре', 'EN'=>'our'))?></div>
            <div><?=$App->Translater(array('RU'=>'н', 'EN'=>'zer'))?><span>0</span><?=$App->Translater(array('RU'=>'ль', 'EN'=>''))?></div>
            <div><span>4</span><?=$App->Translater(array('RU'=>'етыре', 'EN'=>'our'))?></div>
        </div>
    </div>
</div>
