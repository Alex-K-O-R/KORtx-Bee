<?php
namespace app\pages;

trait PageThemes {
    /**
     * @var $this Page
     */
    public function LoadThemes(): void
    {
        require_once($_SERVER['DOCUMENT_ROOT'].'/display/general/themes/common.php');
        $this->Themes()->addTheme(new Theme('cmn', 'header_cmn.php', 'footer.php', $style, $js));

        require_once($_SERVER['DOCUMENT_ROOT'].'/display/general/themes/registered.php');
        $this->Themes()->addTheme(new Theme('reg', 'header_reg.php', 'footer.php', $style, $js));
    }
}