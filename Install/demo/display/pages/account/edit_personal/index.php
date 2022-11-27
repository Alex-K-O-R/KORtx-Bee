<?php
use app\WebUIApplication;
use app\display\nodes\Warning\Warning;
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

?>
<div class="content-wrapper">
    <?
    if($this->Properties()->getState()==='novice'){
        ?><br/><?
        require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/others/Warning.php';
        new Warning("", $App->Translater(array('RU'=>'Впервые на сайте? Пожалуйста, добавьте информацию о себе.', 'EN'=>'First time here? Please, provide some info about yourself.')));
    }
    ?>
    <form action="<?=Pages::personal_edit?>?debug" class="ui form account-form" method="POST" enctype= "multipart/form-data">
    <div class="tab-wrapper row no-gutters">
        <div class="tab-content active col-lg-12" style="display: block;">


            <div class="one-tr personal">
                <div class="content">
                    <div class="edit-button decline">
                        <? if ($this->Properties()->getState()==='data-saved') {?>
                            <img src="/Install/demo/display/img/icons/back.gif">
                        <? } else {?>
                            <img src="/Install/demo/display/img/icons/cancel.png">
                        <? } ?>
                        <a href="<?=Pages::personal_cabinet?>">
                            <button type="button" class="ui button fluid save-btn">
                                <? if ($this->Properties()->getState()==='data-saved') {?>
                                    <?=$App->Translater(array('RU'=>'Вернуться в кабинет', 'EN'=>'Return to cabinet'))?>
                                <? } else {?>
                                    <?=$App->Translater(array('RU'=>'Не сохранять', 'EN'=>'Cancel'))?>
                                <? } ?>
                            </button>
                        </a>
                    </div>
                    <div class="edit-button save">
                        <img src="/Install/demo/display/img/icons/accept.png">
                        <button type="submit" class="ui button fluid save-btn"><?=$App->Translater(array('RU'=>'Сохранить', 'EN'=>'Save changes'))?></button>
                    </div>

                    <div class="user-info">
                        <div class="block_left">
                            <div class="md">
                                <div class="logo">
                                    <div style="width: 100%; text-align: center">
                                        <input class="avatar_select" title="<?=$App->Translater(array('RU'=>'Выберите картинку для аватара', 'EN'=>'Select avatar picture'))?>" type="file" name="avatar" style="background-image: url('<?=$User->getAvatarImageUrl()?>');" />
                                    </div>
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
                                <b><?=$App->Translater(array('RU'=>'Имя', 'EN'=>'Name'))?>:</b>
                                    <div class="div-block-31">
                                        <input type="text" class="text-field-6" maxlength="256" name="uname" value="<?=$User->getDName()?>" />
                                    </div>
                                <br>
                                <b><?=$App->Translater(array('RU'=>'Фамилия', 'EN'=>'Surname'))?>:</b>
                                    <div class="div-block-31">
                                        <input type="text" class="text-field-6" maxlength="256" name="usname" value="<?=$User->getDSurname()?>" />
                                    </div>
                                <br>
                                <b><?=$App->Translater(array('RU'=>'Моя профессия', 'EN'=>'My occupation'))?>:</b>
                                    <div class="div-block-31">
                                        <input type="text" class="text-field-6" maxlength="256" name="job" value="<?=$User->getDProfession()?>" />
                                    </div>
                                <br>
                                <b><?=$App->Translater(array('RU'=>'Язык по умолчанию', 'EN'=>'Default language'))?>:</b>
                                <span class="pad">
                                    <select name="langdef" class="w-select">
                                        <?
                                        $lang_to_autoselect = $User->getLastSelectedLangId();
                                        foreach($this->Application()->getSupportedLanguageInfo() as $lang){
                                            ?>
                                            <option value="<?=$lang->getAcronym()?>"<?=($lang->getAcronym()==$lang_to_autoselect)?' selected':''?>><?=$lang->getFullname()?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                </span><br>
                                <b><?=$App->Translater(array('RU'=>'Краткая информация о себе', 'EN'=>'About (shortly)'))?>:</b>
                                <br/>
                                <div<?=$User->getDGeneralInfo()?'':' class="empty_info"'?>>
                                    <div class="div-block-31">
                                        <textarea class="w-input" rows="4" type="text" class="text-field-6" maxlength="256" name="general"><?=$User->getDGeneralInfo()?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="block_right">
                            <h4>
                                <?=$App->Translater(array('RU'=>'О себе (подробно)', 'EN'=>'About (details)'))?>:
                            </h4><br/>
                            <?
                            require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/editors/TextEditor.php';
                            new \app\display\nodes\TextEditor(
                                'about_detailed', 'details', $User->getDAdditionalInfo());
                            ?>
                        </div>
                    </div>

    <br/><br/><br/>
                    <div class="edit-button decline">
                        <? if ($this->Properties()->getState()==='data-saved') {?>
                            <img src="/Install/demo/display/img/icons/back.gif">
                        <? } else {?>
                            <img src="/Install/demo/display/img/icons/cancel.png">
                        <? } ?>
                        <a href="<?=Pages::personal_cabinet?>">
                            <button type="button" class="ui button fluid save-btn">
                                <? if ($this->Properties()->getState()==='data-saved') {?>
                                    <?=$App->Translater(array('RU'=>'Вернуться в кабинет', 'EN'=>'Return to cabinet'))?>
                                <? } else {?>
                                    <?=$App->Translater(array('RU'=>'Не сохранять', 'EN'=>'Cancel'))?>
                                <? } ?>
                            </button>
                        </a>
                    </div>
                    <div class="edit-button save">
                        <img src="/Install/demo/display/img/icons/accept.png">
                        <button type="submit" class="ui button fluid save-btn"><?=$App->Translater(array('RU'=>'Сохранить', 'EN'=>'Save changes'))?></button>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </form>
</div>

