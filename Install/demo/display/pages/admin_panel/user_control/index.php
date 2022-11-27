<?php
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
?>
<div class="content-wrapper">
    <div class="form-block w-form">
        <form class="form-2" action="<?=Pages::user_control?>" method="GET">
            <input type="hidden" name="<?=Application::searchIsRequiredCodeWord?>">
            <div>
                <strong class="bold-text"><?=$App->Translater(array('RU'=>'Поиск аккаунтов', 'EN'=>'Account search'))?></strong>
            </div>
            <div class="div-block-32">
                <input type="text" name="login" maxlength="256"
                       placeholder="<?=$App->Translater(array('RU'=>'Логин', 'EN'=>'Login'))?>" class="text-field-3 w-input" />
                <input type="text" name="name" maxlength="256"
                       placeholder="<?=$App->Translater(array('RU'=>'Фамилия, Имя', 'EN'=>'Name, Surname'))?>" class="text-field-4 w-input" />
                <div class="div-block-25">
                    <button type="submit" class="button-2 w-button">
                        <?=$App->Translater(array('RU'=>'Поиск', 'EN'=>'Search'))?>
                    </button>
                    <img src="/Install/demo/display/img/icons/5bca43ae34d6c752974e02e0_lens.png" alt="" class="image-12" />
                </div>
            </div>
        </form>
    </div>



    <div class="form-block-3 w-form">
        <form action="<?=Pages::user_control?>" method="POST">
                <div class="user-control tbl-header">
                    <div class="col-usr"><span><?=$App->Translater(array('RU'=>'Пользователь', 'EN'=>'User account'))?></span></div>
                    <div class="sep">
                        <div class="col-act">
                            <div><?=$App->Translater(array('RU'=>'Активировать', 'EN'=>'Activate'))?></div>
                        </div>
                        <div class="col-blk">
                            <div><?=$App->Translater(array('RU'=>'Заблокировать', 'EN'=>'Block'))?></div>
                        </div>
                        <div class="col-del">
                            <div><?=$App->Translater(array('RU'=>'Удалить', 'EN'=>'Delete'))?></div>
                        </div>
                    </div>
                </div>

                <?if($Users){?>
                    <?foreach($Users as &$usr){?>
                        <div class="user-control tbl-row">
                            <div class="col-usr">
                                <span><?=$usr->getLogin()?></span>
                                 <?=((trim($usr->getFullNameFioOrLogin())==trim($usr->getLogin()))?'':'&nbsp;('.$usr->getFullNameFioOrLogin().')')?></div>
                            <div class="sep">
                                <div class="col-act">
                                    <div>
                                        <input type="checkbox" name="act[]"<?=$usr->getActivated()
                                            ?' disabled="disabled" checked="checked" title="'.
                                                $App->Translater(array('RU'=>'Учётная запись уже активирована.', 'EN'=>'Had been already activated.')).'" ':' '?>
                                                value="<?=$usr->getUserId()?>" />
                                    </div>
                                </div>
                                <div class="col-blk">
                                    <div>
                                        <input type="checkbox" name="blk[]"<?=$usr->getIsBlocked()
                                            ?' checked="checked"':''?> value="<?=$usr->getUserId()?>" onchange="$(this).next('input').click();" />
                                        <input hidden="hidden" type="checkbox" name="deblk[]"<?=!$usr->getIsBlocked()
                                            ?' checked="checked"':''?> value="<?=$usr->getUserId()?>" />
                                    </div>
                                </div>
                                <div class="col-del">
                                    <div>
                                        <input type="checkbox" name="del[]" value="<?=$usr->getUserId()?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                <script type="text/javascript">
                    $('.user-control.tbl-row .col-del input').on('change', function(e){
                        if($(this).is(':checked') && $('.user-control.tbl-row .col-del input:checked').size()==1 && $('.user-control.tbl-row .col-del input.warned-already-_-').size()==0){
                            alert('<?=$App->Translater(array('RU'=>'Внимание! Операция необратима. Все данные выбранного пользователя будут стёрты.', 'EN'=>'Warning! Operation is not revertable. All data of selected user will be erased.'))?>');
                            $(this).addClass('warned-already-_-');
                        }
                    });
                </script>
                    <? if(count($Users)<$Totals) {?>
                        <h3 style="cursor: pointer; margin-left: 4vw;" title="<?=$App->Translater(array('RU'=>'Используйте фильтр для поиска среди пользователей.', 'EN'=>'Use filter to search through accounts.'))?>">...</h3>
                    <? } ?>
                <div class="div-block-25" style="float: right; margin: 0 4vw 0 0;">
                    <button type="submit" class="button-2 w-button">
                        <?=$App->Translater(array('RU'=>'Применить', 'EN'=>'Apply'))?>
                    </button>
                </div>
                <br/><br/>
              <?  } else {?>
                <div class="form-block-3 w-form">
                    <h3><?=$App->Translater(array('RU'=>'Нет данных для отображения.', 'EN'=>'No data to display.'))?></h3>
                </div>
                <? } ?>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        new KORtx.Us.KFormFiller(
            'form.form-2',
            <?=$search ? $search->getJsFilterRepresentation() : ''?>
        );
    });
</script>