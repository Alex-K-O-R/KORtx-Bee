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
<br/><br/><br/><br/>
<?=$this->Properties()->getH1()?>
<br/><br/>
<?=$App->Translater(array('RU'=>'Инфа, инфа, много инфы', 'EN'=>'Info, info, many info'))?>.