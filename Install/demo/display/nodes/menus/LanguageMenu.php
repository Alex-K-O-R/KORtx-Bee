<?
namespace app\display\nodes\menu;
use app\Application;
use app\Pages;
use app\models\inner\LanguageMDL;

class LanguageMenu  {
    /**
     * @param Application $App
     * @param LanguageMDL[] $languages
     */
    public static function Draw($App, $languages){
        $language = null;
        foreach($languages as $lang){
            if($lang->getLangId()==$App->getGlobalFilter()->getPortalLanguageInfo()->getLangId()){$language = $lang;}
        }
        ?>
        <div class="header__lang-selector" id="app-language-select">
            <div class="lang-selector">
                <div class="lang-selector__current" title="<?=$language->getFullname()?>"><?=$language->getAcronym()?></div>
            </div>
            <div class="lang-selector__inner">
                <ul class="lang-selector__list">
                    <? foreach($languages as $lang){
                    if($lang->getLangId()==$App->getGlobalFilter()->getPortalLanguageInfo()->getLangId()){continue;}
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
}


?>