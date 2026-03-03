<?php


namespace app\nodes\menus\LanguageMenu;


use app\models\inner\LanguageMDL;
use app\nodes\NodeOptions;

class LanguageMenuOptions extends NodeOptions
{
    const LANGUAGES = 'langs';

    /**
     * LanguageMenuOptions constructor.
     * @param LanguageMDL[] $sectionsInfo
     */
    public static function buildFromParameters(array $sectionsInfo = []): NodeOptions
    {
        $options = new static();
        $options->addVar(self::LANGUAGES, $sectionsInfo);
        return $options;
    }

}