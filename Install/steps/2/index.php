<?
use app\Application;
?>
<input name="step" type="text" value="2" hidden="hidden">
<div class="registration">
    <h2><?=Application::GlobalTransliter(array(
            'RU'=>'Шаг 2.'
        , 'EN'=>'Step 2.'), $lang)?></h2>
    <p>
        <?=Application::GlobalTransliter(array(
            'RU'=>'Выберите тип БД, которая будет использоваться в проекте и укажите параметры доступа'
        , 'EN'=>'Select DB type that you are going to use and provide the access parameters'), $lang)?>:
    </p>

    <div class="split">
        <div class="field registration-status-item">
            <input id="radio-pgsql" type="radio" name="db_type" value="pgsql" class="registration-radio-hidden">
            <label for="radio-pgsql">
                    <span class="registration-status-btn">
                        <img src="/Install/display/img/postgresql_logo_multi.ico" alt="Postgres logo" class="db-icon">
                        <p class="registration-status-name">PostgreSQL</p>
                    </span>
            </label>

            <input id="radio-maria" type="radio" name="db_type" value="mysql" class="registration-radio-hidden">
            <label for="radio-maria">
                    <span class="registration-status-btn">
                        <img src="/Install/display/img/MDB-HLogo.png" alt="MariaDB logo" class="db-icon">
                        <p class="registration-status-name">MariaDB</p>
                    </span>
            </label>

            <!--<input id="radio-mysql" type="radio" name="db_type" value="mysql" class="registration-radio-hidden">
            <label for="radio-mysql">
                    <span class="registration-status-btn">
                        <img src="/Install/display/img/sakila.png" alt="MySql logo" class="db-icon" style="cursor: progress;" title="&mdash; Are you sure?">
                        <p class="registration-status-name">MySQL</p>
                    </span>
            </label>-->

        </div>
    </div>
    <div class="split lborder">
        <div class="field">
            <label>
                <?=Application::GlobalTransliter(array(
                    'RU'=>'Адрес компьютера с БД.'
                , 'EN'=>'DB server address'), $lang)?>
            </label>
            <div class="input-with-icon">
                <input type="text" value="" name="db_host" placeholder="127.0.0.1">
                <i class="input-icon">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="#000000" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                </i>
            </div>
        </div>
        <div class="field">
            <label>
                <?=Application::GlobalTransliter(array(
                    'RU'=>'Пользователь для авторизации в БД.'
                , 'EN'=>'DB authentification user'), $lang)?>
            </label>
            <div class="input-with-icon">
                <input type="text" value="" name="db_user" placeholder="user">
                <i class="input-icon">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="#000000" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                </i>
            </div>
        </div>
        <div class="field">
            <label>
                <?=Application::GlobalTransliter(array(
                    'RU'=>'Пароль для авторизации в БД.'
                , 'EN'=>'DB authentification password'), $lang)?>
            </label>
            <div class="input-with-icon">
                <input type="password" name="db_pass" placeholder="password">
                <i class="input-icon">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="#000000" d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                    </svg>
                </i>
                <i class="input-tooltip input-icon" title="Show password.">
                    <svg class="hide" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.999" height="19.999" viewBox="0 0 14 14">
                        <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACClBMVEUAAADJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJyckAAABNfZ5mAAAArHRSTlMAFU94ThQSV6icPgYykc2GICFDOiYOBTyiwHMTA0qXraaJXTERCGLQYRA0jbTM2tKvbioCBMYZNlh1qev91Io5Cix26EIHL9jKK3mymZ68QDeOvpium1trhF+Mk7q4Xjh7Z0HsJXSWO5rCx1R9bAx+FlAuyZK2b9H2VXqzImOxoFMeRaRlPZDn2Sgbn7lWHz/pSR1N+LsXAcXh3LeLy9+hHEd8sMGBUVlaUpSdlF5VaAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFxEAABcRAcom8z8AAAFiSURBVBjTY2BkYmZhZUADbOwcnFzogtw8vHz8AgwMglxCwiKiMFExcQlJKWkZWTl5BUUlZRWoqKqaurqGppa2jq6evoGhkTFIzESEWW+NKZOZuYWllbWNrZ29A1DM0UnH1tpZwkVVxtWN193Zg9dFlMHTy9vHx1fCz8cfqCsgkIMzKFiWwTIkNMwp3J9fV0KZgcEhPCIySieaISY2Lj4hUTTJNDkqhcEkNY0pPSOTISs2Oz4nN0+EY01yfoFYblphUXQxQ0kpv6xTmZlKbnmFeiKbeVhlVbUVQ1KUgZ1vtItJTW1UXX1DI2tTs1ULg0NrW3F7R3Qnt0NXYXdPnINgbW8f0MZ+n/Y1E0omTjKbrDNlapPDNLbpoCCJ558xc9bskjkKc+fNm99qDPG88YKFixabZs1YIrG0Pqs7ACrK4Noovmz5ipUBAuaryuc4iiEC1hisgmt1T/gC9DDXZPKREgEAsolad4nv9EgAAAAASUVORK5CYII=" width="14" height="14"/>
                    </svg>
                    <svg class="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.999" height="12.856" viewBox="0 0 14 9">
                        <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAANCAMAAACejr5sAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABlVBMVEUAAADJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJycnJyckAAACAQKT1AAAAhXRSTlMAByRVkL/OzdLPulgmBEGCudzivKvY+fPZt4VDBRFLoOzvrW99xPbnzKJNEg6wu57Adv78spa4XA9iW6mA/aRQXa7BCTihx+CxMwZe1K8qlfebJ6xoY+bkZbRawxgWd8v1emDCcR9pymwlHm2+XxCnnEJGT5lUE0SKqI9+jClhmLO2ZCsIJevwOAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFxEAABcRAcom8z8AAAECSURBVBjTY2BgYGBkYmZhZWPn4ORi4eZhZAADXj5+AUEhYRFRMXEJSSlpGZCYrJw8u4KikrKKqlprq7qGppY2A4MOt66evq6BsiGbuFGrsYmpmYm5BQO3iJmllam1jbVta2urmJ29g6SjOYOuk6Wzi6uGm3srCHh4enkbmDD4+PoxMPkHgIVabQODGHiCfRhMnEK8vELDIILhEc4yoayRDFHB0aExsXHxCa2tamyJMbFJyb7eDDpRetEpqUxpSunpvhmZWdlOObl5DAza9vmqBd6FRcXFRdIOJdHWpWUg18uUV/hWVlUnJqpUVerWFEH9yVhbV99g1tgo2VDS1NwCFAAAeRdAHNlS8/cAAAAASUVORK5CYII=" width="14" height="9"/>
                    </svg>
                </i>
            </div>
        </div>
   </div>
    <div class="field">
        <button type="submit" class="ui onexpo-form-btn registration-button">
            <?=Application::GlobalTransliter(array('RU'=>'Продолжить', 'EN'=>'Continue'), $lang)?>
        </button>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        new KORtx.KShowPassword('i.input-tooltip.input-icon');
    });
</script>