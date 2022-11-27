<?
namespace app\display\nodes\menu;

use app\utilities\inner\menus\MenuRecModel;
use app\utilities\inner\menus\Menu;

abstract class TopMenuTMPLT extends Menu {
    public function Draw($menu){
        ?>

        <div class="nav__tr">
            <ul class="tab-menu">
        <?
            foreach($menu as $im){
                /**
                 * @var MenuRecModel $im
                 */
                ?>
                    <li<?=($im->is_selected)?' class="active"':''?>><a href="<?=$im->href?>"><?=$this->Page->Application()->Translater($im->name)?></a></li>
                <?
            }
        ?>
            </ul>
        </div>
    <?
    }
}

?>