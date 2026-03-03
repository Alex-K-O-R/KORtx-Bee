<?php

namespace app\nodes\menus\TopGeneralMenu;

use app\Application;
use app\nodes\menus\MenuRecModel;
use app\nodes\NodeOptions;
use app\utilities\inner\CIE;

class TopGeneralMenuOptions extends NodeOptions
{
    const SECTIONS = 'sections';

    /**
     * @param ?Application $appContext
     */
    public static function buildFromParameters($appContext = null): NodeOptions
    {
        $options = new static();

        if (CIE::l($appContext->getUserInfo())) {
            $sectionsInfo = Registered::buildSections($appContext);
        } else {
            $sectionsInfo = Common::buildSections($appContext);
        }

        $options->addVar(self::SECTIONS, $sectionsInfo);

        return $options;
    }

    /**
     * @return MenuRecModel[]
     */
    public function getSections()
    {
        return $this->getVar(self::SECTIONS);
    }
}