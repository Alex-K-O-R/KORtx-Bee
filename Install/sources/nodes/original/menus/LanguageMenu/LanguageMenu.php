<?
namespace app\nodes\menus\LanguageMenu;

use app\nodes\Node;
use app\nodes\NodeOptions;

class LanguageMenu extends Node {
    /**
     * @param LanguageMenuOptions $languages
     */
    protected function Render(?NodeOptions $languages = null): void
    {
        $language = null;

        foreach($languages->{LanguageMenuOptions::LANGUAGES} as $lang){
            if($lang->getLangId() == $this->App->getGlobalFilter()->getPortalLanguageInfo()->getLangId())
            {
                $language = $lang;
            }
        }

        ?>
        <div class="header__lang-selector" id="app-language-select">
            <div class="lang-selector">
                <div class="lang-selector__current" title="<?=$language->getFullname()?>"><?=$language->getAcronym()?></div>
            </div>
            <div class="lang-selector__inner">
                <ul class="lang-selector__list">
                    <? foreach($languages->{LanguageMenuOptions::LANGUAGES} as $lang){
                    if($lang->getLangId()==$this->App->getGlobalFilter()->getPortalLanguageInfo()->getLangId()){continue;}
                    ?>
                    <li class="lang-selector__item" title="<?=$lang->getFullname()?>">
                        <input type="hidden" class="abc" name="use-only-language" value="<?=$lang->getAcronym()?>"/>
                        <span class="lang-selector__link"><?=$lang->getAcronym()?></span>
                    </li>
                    <? } ?>
                </ul>
            </div>
        </div>
    <?
    }

    /**
     * @param $contextPage
     * @param LanguageMenuOptions $params
     */
    public static function Draw($contextPage, $params): void
    {
        parent::Draw($contextPage, $params);
    }
}
?>