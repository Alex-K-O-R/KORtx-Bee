<?
use app\Application;
?>
<input name="step" type="text" value="3" hidden="hidden">

<div class="registration">
    <h2><?=Application::GlobalTransliter(array(
            'RU'=>'Шаг 3.'
        , 'EN'=>'Step 3.'), $lang)?></h2>
    <p class="regular">
        <?=Application::GlobalTransliter(array(
            'RU'=>'Придумайте и укажите уникальное слово-идентификатор для данного сайта. На базе него будет сформировано название БД и созданы таблицы (<b>Внимание!</b> В случае совпадения названий, таблицы будут пересозданы, а существующие данные утеряны), по следующей формуле:'
        , 'EN'=>'Provide the unique keyword identificator for current site project. Depending on this keyword, database name and tables would get their names (<b>Caution!</b> In case of existing tables with such names they are to be recreated, existing information will be lost), by the next rule:'), $lang)?>
        <br/>
    </p>
    <p>
        <?=Application::GlobalTransliter(array('RU'=>'Название БД', 'EN'=>'Database name'), $lang)?>: <b>keyword_</b>series
        </br>
        <?=Application::GlobalTransliter(array('RU'=>'Названия всех таблиц проекта', 'EN'=>'All project tables names'), $lang)?>: <b>keyword_</b>tableName
    </p>

    <div class="field">
        <label><?=Application::GlobalTransliter(array('RU'=>'Уникальное слово-идентификатор', 'EN'=>'Unique keyword identificator'), $lang)?>:</label>
        <div class="input-with-icon">
            <input type="text" value="" name="project_keyword" placeholder="my_keyword">
            <i class="input-icon">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
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

