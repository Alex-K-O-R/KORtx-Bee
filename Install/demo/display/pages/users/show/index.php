<?
use app\pages\Page;
use app\models\UserMDL;
use app\pages\Pages;

/**
 * @var $this Page
 */
/**
 * @var UserMDL $User
 */
$User = $this->Properties()->getVar('user');
$App = $this->Application();
?>
<div class="content-wrapper">
    <div class="tab-wrapper row no-gutters">
    <div class="nav__tr">
        <ul class="tab-menu breadcrumbs">
            <li><a href="<?=Pages::main?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Home'))?></a></li>
            <li><a href="<?=Pages::user_list?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Все участники', 'EN'=>'All participants'))?></a></li>
            <li><?=$User->getLogin()?></li>
        </ul>
    </div>
    <div class="tab-content active col-lg-12" style="display: block;">
        <h2 class="right">
            <?=$User->getFullNameFioOrLogin('<br />')?>

            <? if ($App->getUserInfo() && $User->getUserId()!=$App->getUserInfo()->getUserId()) {
                $is_favored = in_array($User->getUserId(), $this->Properties()->getVarAsArray('favorite_ids'));
                $title = $is_favored ? $App->Translater(array('RU'=>'В избранном', 'EN'=>'Is in favorites')) : $App->Translater(array('RU'=>'Добавить в избранные', 'EN'=>'Mark as favorite'));
                ?>
                <div class="fav-icon big" data-uid="<?=$User->getUserId()?>" title="<?=$title?>">
                    <?
                    if ($is_favored) {?>
                        <img src="/Install/demo/display/img/icons/fav_icon_on.png">
                    <?} else {?>
                        <img src="/Install/demo/display/img/icons/fav_icon_off.png">
                    <?}?>
                </div>
            <?}?>
        </h2>
        <div class="one-tr">
            <div class="group-span">
                <div class="item__img" style="text-align: center;">
                    <img src="<?=$User->getAvatarImageUrl()?>" style="border-radius: 16px;"/>
                </div>
            </div>
        </div>
        <br/>
        <div class="row bgwhite">
            <div class="col-sm-6 df">
                <div class="group-span">
                        <span><img style="margin-right: 9px;" src="/Install/demo/display/img/icons/head.png">
                            <?=$App->Translater(array('RU'=>'О себе', 'EN'=>'About'))?>:
                        </span>
                        <span>
                        <?
                            if($User->getDAdditionalInfo()||$User->getDGeneralInfo()) {
                                ?>
                                <br/><br/><?=($User->getDAdditionalInfo()?$User->getDAdditionalInfo():$User->getDGeneralInfo())?>
                            <?} else {?>
                                <i><?=$App->Translater(array('RU'=>'сильно поскромничали.', 'EN'=>'too shy to share.'))?></i>
                            <?}?>
                        </span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="group-span">
                        <span>
                            <img src="/Install/demo/display/img/icons/date.png">
                            <?=$App->Translater(array('RU'=>'Дата регистрации', 'EN'=>'Registration'))?>:<br/>
                            <?=$User->getAddDate($App->getGlobalFilter()->getPortalLanguageInfo()->getPhpDateMask())?>
                            (<?=$App->Translater(array('RU'=>'последнее посещение', 'EN'=>'last visit'))?>:
                            <? if ($User->getLastLoginDate()) {?>
                                <?=$User->getLastLoginDate($this->Application()->getGlobalFilter()->getPortalLanguageInfo()->getPhpDateMask())?>)
                            <?} else {?>
                                <i><?=$App->Translater(array('RU'=>'никогда ранее', 'EN'=>'never before'))?></i>)
                            <?}?>
                        </span>
                    <br/><br/>
                        <span>
                        <?=$App->Translater(array('RU'=>'Моя профессия', 'EN'=>'My occupation'))?>:
                            <? if ($User->getDProfession()) {?>
                                <a href="#"><?=$User->getDProfession()?></a>
                            <?} else {?>
                                <br/>
                                <i><?=$App->Translater(array('RU'=>'- не указана -', 'EN'=>'- kept in secret -'))?></i>
                            <?}?>
                        </span>
                    <br/><br/>
                        <span>
                            <?=$App->Translater(array('RU'=>'Входит в группу', 'EN'=>'Group member'))?>:<br/>
                            <?
                            if($User->getIsAdmin()) {
                                ?>
                                <b><?=$App->Translater(array('RU'=>'Администраторы', 'EN'=>'Administrators'))?></b>
                            <?} else {?>
                                <?=$App->Translater(array('RU'=>'Пользователи', 'EN'=>'Users'))?>
                            <?}?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<? if($App->getUserInfo()) {?>
    <script type="text/javascript">
        window.onload = function(){
            $('div.fav-icon.big').click(function(e){
                var div = this;
                return MarkIVFavorite(
                    'user',
                    $(div).data("uid"),
                    function(){
                        $(div).attr('title', '<?=$App->Translater(array('RU'=>'В избранном', 'EN'=>'Is in favorites'))?>');
                        $(div).children('img').attr('src','/display/img/icons/fav_icon_on.png');
                    },
                    function(){
                        $(div).attr('title', '<?=$App->Translater(array('RU'=>'Добавить в избранные', 'EN'=>'Mark as favorite'))?>');
                        $(div).children('img').attr('src','/display/img/icons/fav_icon_off.png');
                    }
                );
            });
        }
    </script>
<? } ?>