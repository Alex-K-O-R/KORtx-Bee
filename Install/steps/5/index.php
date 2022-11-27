<?
use app\Application;
?>
<input name="step" type="text" value="5" hidden="hidden">
<p class="regular"><?=Application::GlobalTransliter(array(
        'RU'=>'Остался последний шаг, перед тем как установка завершится, и вы сможете перейти на основной веб-сайт.'
    , 'EN'=>'There is only one thing left before installation process ends, and you\'ll proceed to the website.'), $lang)?>
</p>
<div class="registration">
    <h2><?=Application::GlobalTransliter(array(
            'RU'=>'Шаг 5.'
        , 'EN'=>'Step 5.'), $lang)?></h2>
    <p class="regular"><?=Application::GlobalTransliter(array(
            'RU'=>'Учётная запись администратора успешно создана. Желаете добавить несколько пользователей для демонстрации?'
        , 'EN'=>'Administrator was successfully created. Would you like to create additional users as a demo part?'), $lang)?>
    </p>
    <div class="field">
        <div class="label"><?=Application::GlobalTransliter(array('RU'=>'Добавить демо-пользователей', 'EN'=>'Add demo-users'), $lang)?>:</div>
        <div class="slideCheckbox red">
            <input type="checkbox" id="add_demo" name="add_demo" checked="true" />
            <label for="add_demo"></label>
        </div>
    </div>
    <div class="field">
        <button type="submit" class="ui onexpo-form-btn registration-button">
            <?=Application::GlobalTransliter(array('RU'=>'Продолжить', 'EN'=>'Continue'), $lang)?>
        </button>
    </div>
</div>