<?php
namespace app\pages;

trait PageThemes {
    /**
     * @var $this Page
     */
    public function LoadThemes(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/display/general/themes/common.php');
        $this->themes->addTheme(new Theme('cmn', 'header_cmn.php', 'footer.php', $style, $js));

        include_once($_SERVER['DOCUMENT_ROOT'].'/display/general/themes/registered.php');
        $this->themes->addTheme(new Theme('reg', 'header_reg.php', 'footer.php', $style, $js));
    }
}