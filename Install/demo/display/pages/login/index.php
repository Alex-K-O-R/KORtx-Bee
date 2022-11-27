<?
use app\WebUIApplication;
use app\display\nodes\Warning\Warning;
use app\pages\Page;
use app\pages\Pages;

/**
* @var $this Page
*/
$App = $this->Application();
?>
<div class="content-wrapper">
    <div class="tab-wrapper row no-gutters">
        <div class="nav__tr">
            <ul class="tab-menu breadcrumbs">
                <li><a href="<?=Pages::main?>" class="breadcrumbs-link"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Home'))?></a></li>
                <li><?=$App->Translater(array('RU'=>'Авторизация', 'EN'=>'Authorisation'))?></li>
            </ul>
        </div>
    </div>
    <?
    if($this->Properties()->getState()===WebUIApplication::AUTH_NO_ACCESS){
        ?><br/><?
        require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/others/Warning.php';
        new Warning($App->Translater(array('RU'=>'Ошибка авторизации.', 'EN'=>'Authorization failed.')), $App->Translater(array('RU'=>'Неправильный логин, или пароль!', 'EN'=>'Wrong login or password!')));
    }

    if($this->Properties()->getState()===WebUIApplication::AUTH_NO_ACTIVATION){
        ?><br/><?
        require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/others/Warning.php';
        new Warning(
            $App->Translater(array('RU'=>'Учётная запись не активирована.', 'EN'=>'The account is not activated.'))
            ,$App->Translater(array(
                    'RU'=>'Учётная запись активируется администраторами. Пожалуйста, ожидайте активации.'
                , 'EN'=>'User account is activated by administrators. Please wait.')
            )
        );
    }

    if($this->Properties()->getState()===WebUIApplication::AUTH_BLOCKED){
        ?><br/><?
        require_once $_SERVER['DOCUMENT_ROOT'] . '/display/nodes/others/Warning.php';
        new Warning($App->Translater(array('RU'=>'Учётная запись заблокирована!', 'EN'=>'User account has been blocked!')));
    }
    ?>
    <div class="form-block w-form login">
        <form class="form-2" action="<?=$this->Url()?>?debug" method="POST">
            <div class="frm_center">
                <strong class="bold-text"><?=$App->Translater($this->Properties()->getH1())?></strong>
            </div>
            <div class="frm_center">
                <input class="text-field-6 w-input" type="text" value="<?=$this->Properties()->getVar('login')?>" name="login" placeholder="<?=$App->Translater(array('RU'=>'Логин', 'EN'=>'Login'))?>">
                <div class="text-field-5 w-input">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="#000000" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                </div>
            </div>
            <div class="frm_center">
                <input class="text-field-6 w-input pass" type="password" name="password" placeholder="<?=$App->Translater(array('RU'=>'Пароль', 'EN'=>'Password'))?>">
                <div class="text-field-5 w-input">
                    <div>
                        <i class="input-tooltip input-icon" title="<?=$App->Translater(array('RU'=>'Показать пароль.', 'EN'=>'Show password.'))?>">
                            <svg class="hide" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.999" height="19.999" viewBox="0 0 14 14">
                                <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACClBMVEUAAADJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJyckAAABNfZ5mAAAArHRSTlMAFU94ThQSV6icPgYykc2GICFDOiYOBTyiwHMTA0qXraaJXTERCGLQYRA0jbTM2tKvbioCBMYZNlh1qev91Io5Cix26EIHL9jKK3mymZ68QDeOvpium1trhF+Mk7q4Xjh7Z0HsJXSWO5rCx1R9bAx+FlAuyZK2b9H2VXqzImOxoFMeRaRlPZDn2Sgbn7lWHz/pSR1N+LsXAcXh3LeLy9+hHEd8sMGBUVlaUpSdlF5VaAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFxEAABcRAcom8z8AAAFiSURBVBjTY2BkYmZhZUADbOwcnFzogtw8vHz8AgwMglxCwiKiMFExcQlJKWkZWTl5BUUlZRWoqKqaurqGppa2jq6evoGhkTFIzESEWW+NKZOZuYWllbWNrZ29A1DM0UnH1tpZwkVVxtWN193Zg9dFlMHTy9vHx1fCz8cfqCsgkIMzKFiWwTIkNMwp3J9fV0KZgcEhPCIySieaISY2Lj4hUTTJNDkqhcEkNY0pPSOTISs2Oz4nN0+EY01yfoFYblphUXQxQ0kpv6xTmZlKbnmFeiKbeVhlVbUVQ1KUgZ1vtItJTW1UXX1DI2tTs1ULg0NrW3F7R3Qnt0NXYXdPnINgbW8f0MZ+n/Y1E0omTjKbrDNlapPDNLbpoCCJ558xc9bskjkKc+fNm99qDPG88YKFixabZs1YIrG0Pqs7ACrK4Noovmz5ipUBAuaryuc4iiEC1hisgmt1T/gC9DDXZPKREgEAsolad4nv9EgAAAAASUVORK5CYII=" width="14" height="14"/>
                            </svg>
                            <svg class="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.999" height="12.856" viewBox="0 0 14 9">
                                <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAANCAMAAACejr5sAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABlVBMVEUAAADJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJyckAAACAQKT1AAAAhXRSTlMAByRVkL/OzdLPulgmBEGCudzivKvY+fPZt4VDBRFLoOzvrW99xPbnzKJNEg6wu57Adv78spa4XA9iW6mA/aRQXa7BCTihx+CxMwZe1K8qlfebJ6xoY+bkZbRawxgWd8v1emDCcR9pymwlHm2+XxCnnEJGT5lUE0SKqI9+jClhmLO2ZCsIJevwOAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFxEAABcRAcom8z8AAAECSURBVBjTY2BgYGBkYmZhZWPn4ORi4eZhZAADXj5+AUEhYRFRMXEJSSlpGZCYrJw8u4KikrKKqlprq7qGppY2A4MOt66evq6BsiGbuFGrsYmpmYm5BQO3iJmllam1jbVta2urmJ29g6SjOYOuk6Wzi6uGm3srCHh4enkbmDD4+PoxMPkHgIVabQODGHiCfRhMnEK8vELDIILhEc4yoayRDFHB0aExsXHxCa2tamyJMbFJyb7eDDpRetEpqUxpSunpvhmZWdlOObl5DAza9vmqBd6FRcXFRdIOJdHWpWUg18uUV/hWVlUnJqpUVerWFEH9yVhbV99g1tgo2VDS1NwCFAAAeRdAHNlS8/cAAAAASUVORK5CYII=" width="14" height="9"/>
                            </svg>
                        </i>
                    </div>
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="#000000" d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                    </svg>
                </div>
                <div class="btr-btn">
                    <button type="submit"><?=$App->Translater(array('RU'=>'Войти', 'EN'=>'Enter'))?></button>
                </div>
            </div>
        </form>
        <div class="field relative-links">
            <!--<span class="one">
                <a href="<?/*=*/?>" title="<?/*=$App->Translater(array('RU'=>'Перейти к сбросу пароля', 'EN'=>'Go to password reset'))*/?>"><?/*=$App->Translater(array('RU'=>'Забыли пароль?', 'EN'=>'Forgot password?'))*/?></a>
            </span>-->
            <span class="two">
                <a href="<?=Pages::register?>" title="<?=$App->Translater(array('RU'=>'Регистрация новой учётной записи', 'EN'=>'Register a new account'))?>"><?=$App->Translater(array('RU'=>'Создать аккаунт', 'EN'=>'Create an account'))?></a>
            </span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        new KORtx.KShowPassword('i.input-tooltip.input-icon', '.text-field-6.w-input.pass');
    });
</script>
