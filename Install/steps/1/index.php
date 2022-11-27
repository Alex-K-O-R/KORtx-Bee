<?
use app\Application;
?>
<input name="step" type="text" value="1" hidden="hidden">
<h1>
    <?=Application::GlobalTransliter(array('RU'=>'Добро пожаловать в установку KORtx Bee.', 'EN'=>'Welcome to KORtx Bee installation manager.'), $lang)?>
</h1>
<p class="regular"><?=Application::GlobalTransliter(array(
        'RU'=>'KORtx Bee &mdash; это удобное окружение для разработки сайтов и сервисов.

        В основе его находится приложение MVC. Подробности станут доступны по завершению установки.'
    , 'EN'=>'KORtx Bee is the comfy site/service framework.

        Basis of this framework is MVC Application. Details will be available after the installation is complete.'), $lang)?>
</p>
<p class="regular"><?=Application::GlobalTransliter(array(
        'RU'=>'Прежде чем продолжить, убедитесь, что вы уже развернули поставщика базы данных (PostgreSQL, или MariaDB) и определили пользователя с правами на создание БД.'
    , 'EN'=>'Before continue, be sure, that you had already installed database provider (PostgreSQL or MariaDB) and are able to provide database user with rights to create DB.'), $lang)?>
</p>
<p class="regular">
    <?=Application::GlobalTransliter(array(
        'RU'=>'Наслаждайтесь...'
    , 'EN'=>'Enjoy...'), $lang)?>
</p>

<div class="registration">
    <h2><?=Application::GlobalTransliter(array(
            'RU'=>'Шаг 1.'
        , 'EN'=>'Step 1.'), $lang)?></h2>
    <p>
        <?=Application::GlobalTransliter(array(
            'RU'=>'Укажите адрес сайта, к которому будет привязано окружение (например, <i>https://kortxbee.com</i>).'
        , 'EN'=>'Provide an url address that will be associated with work environment (example: <i>https://kortxbee.com</i>).'), $lang)?>
    </p>


        <div class="field">
            <label><?=Application::GlobalTransliter(array('RU'=>'Адрес сайта с http(s)', 'EN'=>'Site address with http(s)'), $lang)?>:</label>
            <div class="input-with-icon">
                <input type="text" value="" name="project_url" placeholder="<?=$_SERVER['SERVER_NAME']?>">
                <i class="input-icon">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <!--
                        background-image: url("data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9JzMwMHB4JyB3aWR0aD0nMzAwcHgnICBmaWxsPSIjMDAwMDAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTUxLDQ5LjlMMjQuNCwyMy40Yy00LjItNC4yLTQuMi0xMSwwLTE1LjJjNC4yLTQuMiwxMS00LjIsMTUuMywwbDM3LjMsMzcuMmMyLjUsMi41LDIuNSw2LjYsMCw5LjJMMzkuNiw5MS43ICBjLTQuMiw0LjItMTEsNC4yLTE1LjMsMGMtNC4yLTQuMi00LjItMTEsMC0xNS4yTDUxLDQ5Ljl6Ij48L3BhdGg+PC9zdmc+");
                        -->
                        <path fill="#000000" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                </i>
            </div>
        </div>
        <div class="field">
            <button type="submit" class="ui onexpo-form-btn registration-button">
                <?=Application::GlobalTransliter(array('RU'=>'Продолжить', 'EN'=>'Continue'), $lang)?>
            </button>
        </div>
</div>