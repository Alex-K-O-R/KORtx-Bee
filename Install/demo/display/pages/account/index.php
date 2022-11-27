<?php
use app\WebUIApplication;
use app\models\UserMDL;
use app\pages\Page;
use app\pages\Pages;

/**
 * @var Page $this
 * @var WebUIApplication $App
 */
$App = $this->Application();

/**
 * @var UserMDL $User
 */
$User = $this->Properties()->getVar('user');

/**
 * @var UserMDL[] $Favs
 */
$Favs = $this->Properties()->getVarAsArray('favorite_users');

$totals = $this->Properties()->getVar('totals');
$page = $this->Properties()->getVar('page');
$cpcty = $this->Properties()->getVar('page_volume');
?>
<div class="content-wrapper">
    <div class="tab-wrapper row no-gutters">
    <div class="tab-content active col-lg-12" style="display: block;">


        <div class="one-tr personal">
            <div class="content">
                <div class="edit-button">
                    <img src="/Install/demo/display/img/icons/consoleButtonGlyph.png">
                    <a href="<?=Pages::personal_edit?>"><button type="button"><?=$App->Translater(array('RU'=>'Редактировать личные данные', 'EN'=>'Edit personal data'))?></button></a>
                </div>

                    <div class="user-info">
                        <div class="block_left">
                            <div class="md">
                                <div class="logo">
                                    <img src="<?=$User->getAvatarImageUrl()?>">
                                    <div class="tab tab_active">
                                        <?
                                        if($User->getIsAdmin()) {
                                            ?>
                                            <b><?=$App->Translater(array('RU'=>'Администратор', 'EN'=>'Administrator'))?></b>
                                        <?} else {?>
                                            <?=$App->Translater(array('RU'=>'Пользователь', 'EN'=>'User'))?>
                                        <?}?>
                                    </div>
                                    <span class="title"><?=$User->getLogin()?></span>
                                </div>
                                <br/>
                                <b><?=$App->Translater(array('RU'=>'Дата регистрации', 'EN'=>'Registration'))?>:</b> <span><?=$User->getAddDate($App->getGlobalFilter()->getPortalLanguageInfo()->getPhpDateMask())?></span><br>
                                <br/>
                                <b><?=$App->Translater(array('RU'=>'Имя', 'EN'=>'Name'))?>:</b> <span><?=$User->getDName()?$User->getDName():'---'?></span><br>
                                <b><?=$App->Translater(array('RU'=>'Фамилия', 'EN'=>'Surname'))?>:</b> <span><?=$User->getDSurname()?$User->getDSurname():'---'?></span><br>
                                <b><?=$App->Translater(array('RU'=>'Моя профессия', 'EN'=>'My occupation'))?>:</b> <span><?=$User->getDProfession()?$User->getDProfession():'---'?></span><br>
                                <b><?=$App->Translater(array('RU'=>'Язык по умолчанию', 'EN'=>'Default language'))?>:</b> <span class="pad"><?=$User->getLastSelectedLangId()?$User->getLastSelectedLangId():'---'?></span><br>
                                <b><?=$App->Translater(array('RU'=>'Краткая информация о себе', 'EN'=>'About (shortly)'))?>:</b>
                                <br/>
                                <div<?=$User->getDGeneralInfo()?'':' class="empty_info"'?>>
                                    <?=$User->getDGeneralInfo()?$User->getDGeneralInfo():'---'?>
                                </div>
                            </div>
                        </div>


                        <div class="block_right">
                            <h4>
                                <?=$App->Translater(array('RU'=>'О себе (подробно)', 'EN'=>'About (details)'))?>:
                            </h4><br/>
                            <?=$User->getDAdditionalInfo()?$User->getDAdditionalInfo():'---'?>
                        </div>
                    </div>

            </div>
        </div>

        <?
        if(count($Favs)) {
        ?>
        <div class="two-tr personal">
            <h2>
                <div>
                    <?
                    if($totals>$cpcty && $page>1){
                    ?>
                    <a href="?page=<?=$page-1?>"><i class="fas fa-angle-left"></i> </a>
                    <? } ?>
                        <?=$App->Translater(array('RU'=>'Мои избранные', 'EN'=>'My favorites'))?>
                    <?
                    if($totals>$cpcty && $page<($totals/$cpcty)){
                    ?>
                    <a href="?page=<?=$page+1?>"> <i class="fas fa-angle-right"></i></a>
                    <? } ?>
                </div>
            </h2>
            <div class="scroll-y">
                <?
                foreach($Favs as &$usr){
                ?>
                    <div class="row">
                        <div class="col-sm-6 df">
                            <div class="item__1">
                                <div class="item__img">
                                    <img src="<?=$usr->getAvatarImageUrl()?>">
                                </div>
                                <div class="item__text">
                                    <h3><?=$usr->getFullNameFioOrLogin()?></h3>
                                        <span>
                                            <?=$App->Translater(array('RU'=>'Моя профессия', 'EN'=>'My occupation'))?>:
                                            <? if ($usr->getDProfession()) {?>
                                                <a href="#"><?=$usr->getDProfession()?></a>
                                            <?} else {?>
                                                <br/>
                                                <i><?=$App->Translater(array('RU'=>'- не указана -', 'EN'=>'- kept in secret -'))?></i>
                                            <?}?>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="group-span">
                                    <span>
                                        <img src="/Install/demo/display/img/icons/date.png">
                                        <?=$App->Translater(array('RU'=>'Дата регистрации', 'EN'=>'Registration'))?>:
                                        <?=$usr->getAddDate($App->getGlobalFilter()->getPortalLanguageInfo()->getPhpDateMask())?>
                                    </span><br><br>
                                <?
                                if($usr->getDGeneralInfo()) {
                                ?>
                                <span><img style="margin-right: 9px;" src="/Install/demo/display/img/icons/head.png">
                                    <?=$App->Translater(array('RU'=>'Кратко о себе', 'EN'=>'Few words about'))?>:
                                        <br/><?=$usr->getDGeneralInfo()?></span><br><br/>
                                    <span>
                                    <?}?>
                                        <?=$App->Translater(array('RU'=>'Входит в группу', 'EN'=>'Group member'))?>:<br/>
                                        <?
                                        if($usr->getIsAdmin()) {
                                            ?>
                                            <b><?=$App->Translater(array('RU'=>'Администраторы', 'EN'=>'Administrators'))?></b>
                                        <?} else {?>
                                            <?=$App->Translater(array('RU'=>'Пользователи', 'EN'=>'Users'))?>
                                        <?}?>
                                    </span>
                                <br><br/>
                                    <span><a href="<?=Pages::getDynamic(Pages::user_info, array($usr->getUserId()))?>">
                                            <?=$App->Translater(array('RU'=>'Перейти в профиль', 'EN'=>'Open profile'))?>...
                                        </a></span>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
        <? } ?>

    </div>
    </div>
</div>











