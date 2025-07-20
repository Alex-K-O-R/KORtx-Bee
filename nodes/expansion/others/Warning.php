<?
namespace app\display\nodes\Warning;
use app\Application;
use app\Pages;


class Warning   {
public function __construct($header = null, $text = null){
    self::Draw($header, $text);
}

private static function Draw($header, $text){
    ?>
    <div class="notice">
        <? if ($header!==null){?><h2><?=$header?></h2><?}?>
        <? if ($text!==null){?><?=$text?><?}?>
        <div class="close" onclick="this.parentNode.remove();">×</div>
    </div>
    <?
    }
}

?>