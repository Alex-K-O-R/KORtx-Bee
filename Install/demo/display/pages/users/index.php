<?
use app\Application;
use app\display\nodes\Paginator\Paginator;
use app\filters\models\GeneralFilterableModel;
use app\pages\Page;
use app\models\UserMDL;
use app\pages\Pages;

/**
 * @var $this Page
 */
$App = $this->Application();

/**
 * @var UserMDL[] $Users
 */
$Users = $this->Properties()->getVarAsArray('users');

$Totals = $this->Properties()->getVar('totals');
/**
 * @var $search GeneralFilterableModel
 */
$search = $this->Properties()->getVar('filter');

$page = intval($this->Properties()->getVar('page'));
$pgMax = intval($this->Properties()->getVar('page_volume'));
?>
<div class="content-wrapper">
    <div class="tab-wrapper row no-gutters">
        <div class="nav__tr">
            <ul class="tab-menu breadcrumbs">
                <li><a href="<?=Pages::main?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Home'))?></a></li>
                <li><?=$App->Translater(array('RU'=>'Все участники', 'EN'=>'All participants'))?></li>
            </ul>
        </div>
    </div>

    <div class="form-block w-form">
        <form class="form-2" action="<?=Pages::user_list?>" method="GET">
            <input type="hidden" name="<?=Application::searchIsRequiredCodeWord?>">
            <div>
                <strong class="bold-text"><?=$App->Translater(array('RU'=>'Поиск людей', 'EN'=>'People search'))?></strong>
            </div>
            <div class="div-block-31">
                <input type="text" class="text-field-6 w-input" maxlength="256" name="about_info"
                       placeholder="<?=$App->Translater(array('RU'=>'Информация о себе', 'EN'=>'About info'))?>" />
            </div>
            <div class="div-block-32">
                <input type="text" name="user_name" maxlength="256"
                       placeholder="<?=$App->Translater(array('RU'=>'Фамилия, Имя', 'EN'=>'Name, Surname'))?>" class="text-field-4 w-input" />
                <input type="text" name="profession" maxlength="256"
                       placeholder="<?=$App->Translater(array('RU'=>'Профессия', 'EN'=>'Profession'))?>" class="text-field-3 w-input" />
                <select id="field" name="activated" class="select-field-2 w-select">
                    <option value="true" selected="selected"><?=$App->Translater(array('RU'=>'Активирован', 'EN'=>'Activated'))?></option>
                    <option value="false"><?=$App->Translater(array('RU'=>'Не активирован', 'EN'=>'Not activated'))?></option>
                </select>
                <div class="div-block-25">
                    <button type="submit" class="button-2 w-button">
                        <?=$App->Translater(array('RU'=>'Поиск', 'EN'=>'Search'))?>
                    </button>
                    <img src="/Install/demo/display/img/icons/5bca43ae34d6c752974e02e0_lens.png" alt="" class="image-12" />
                </div>
            </div>
        </form>
    </div>

        <div class="tab-wrapper row no-gutters">
            <div class="tab-content active col-lg-12" style="display: block;">
                <div class="one-tr">
                    <h2><?=$this->Application()->Translater($this->Properties()->getH1())?> (<?=count($Users)+$pgMax*($page-1)?> <?=$App->Translater(array('RU'=>'из', 'EN'=>'of'))?> <?=$Totals?$Totals:0?>)</h2>
    <?if($Users){?>
        <?foreach($Users as &$usr){?>
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
                                    <? if ($App->getUserInfo() && $usr->getUserId()!=$App->getUserInfo()->getUserId()) {
                                        $is_favored = in_array($usr->getUserId(), $this->Properties()->getVarAsArray('favorite_ids'));
                                        $title = $is_favored ? $App->Translater(array('RU'=>'В избранном', 'EN'=>'Is in favorites')) : $App->Translater(array('RU'=>'Добавить в избранные', 'EN'=>'Mark as favorite'));
                                        ?>
                                        <div class="fav-icon small" data-uid="<?=$usr->getUserId()?>" title="<?=$title?>">
                                            <?
                                            if ($is_favored) {?>
                                            <img src="/Install/demo/display/img/icons/fav_icon_on.png">
                                            <?} else {?>
                                            <img src="/Install/demo/display/img/icons/fav_icon_off.png">
                                            <?}?>
                                        </div>
                                    <?}?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="group-span">
                                    <span>
                                        <img src="/Install/demo/display/img/icons/date.png">
                                        <?=$App->Translater(array('RU'=>'Дата регистрации', 'EN'=>'Registration'))?>:
                                        <?=$usr->getAddDate($App->getGlobalFilter()->getPortalLanguageInfo()->getPhpDateMask())?>
                                        <?
                                        if(!$usr->getActivated()) {
                                        ?>
                                        <span>
                                            <?=$App->Translater(array('RU'=>'(уч. запись не активирована)', 'EN'=>'(account is not activated)'))?>
                                        <span>
                                        <?}?>
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

    <?
        }

    require_once $_SERVER['DOCUMENT_ROOT'].'/display/nodes/others/Paginator.php';
    (new Paginator())->pager($Totals, $pgMax, $page)->render(null,true,true);
    } else {?>
        <div class="form-block-3 w-form">
            <h3><?=$App->Translater(array('RU'=>'Нет данных для отображения.', 'EN'=>'No data to display.'))?></h3>
        </div>
    <? } ?>
                </div>
            </div>
        </div>
    <? if($App->getUserInfo()) {?>
    <script type="text/javascript">
        window.onload = function(){
            $('div.fav-icon.small').click(function(e){
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
</div>
<script type="text/javascript">
    $(function(){
        new KORtx.Us.KFormFiller(
            'form.form-2',
            <?=$search ? $search->getJsFilterRepresentation() : ''?>
        );
    });
</script>