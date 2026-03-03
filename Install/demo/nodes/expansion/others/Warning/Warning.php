<?
namespace app\nodes\others\Warning;


use app\nodes\Node;
use app\nodes\NodeOptions;

class Warning extends Node  {
    /**
     * @param WarningOptions|null $nodeParametersContainer
     */
    protected function Render(?NodeOptions $nodeParametersContainer = null): void
    {
        ?>
        <div class="notice">
            <? if ($nodeParametersContainer->getHeaderArr() !== null) {?>
                <h2>
                <?=$this->App->Translater($nodeParametersContainer->getHeaderArr())?>
                </h2>
            <?}?>
            <? if ($nodeParametersContainer->getTextArr() !== null) {?>
                <?=$this->App->Translater($nodeParametersContainer->getTextArr())?>
            <?}?>
            <div class="close" onclick="this.parentNode.remove();">×</div>
        </div>
        <?
    }
}

?>