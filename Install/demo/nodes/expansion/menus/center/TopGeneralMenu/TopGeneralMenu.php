<?
namespace app\nodes\menus\TopGeneralMenu;

use app\nodes\NodeOptions;
use app\nodes\menus\GenericMenu;

class TopGeneralMenu extends GenericMenu {

    /**
     * @param TopGeneralMenuOptions $nodeParametersContainer
     */
    protected function Render(?NodeOptions $nodeParametersContainer = null): void
    {
        ?>
        <div class="nav__tr">
            <ul class="tab-menu">
        <?
            foreach($nodeParametersContainer->getSections() as $im){
                ?>
                    <li<?=($im->is_selected)?' class="active"':''?>><a href="<?=$im->href?>"><?=$this->App->Translater($im->name)?></a></li>
                <?
            }
        ?>
            </ul>
        </div>
    <?
    }
}

?>