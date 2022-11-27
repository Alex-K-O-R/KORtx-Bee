<?
use app\pages\Pages;
/**
 * @var $this Page
 */
$App = $this->Page()->Application();
?>

        </div>
    </div>
            <div class="static">
                <div style="display: block; width: 100%; height: 10vh"></div>
            </div>
            <div class="footer">
                <div class="left">
                    <div class="KORtx_logo">
                        <div class="Bee">
                            <img src="/Install/display/img/KortX_Bee_logo.png" width="60" class="prize-img">
                        </div>
                        <div class="text">
                            <b>KORtx&nbsp;Bee<i></i></b>
                            <br/>
                            2013<span>&nbsp;</span>-<div class="logo infinum">&infin;</div>
                        </div>
                    </div>
                    <div class="title">
                        <?=$App->Translater(array('RU'=>'Техническая демонстрация', 'EN'=>'A tech demo website'))?>
                    </div>
                </div>
                <div class="right" style="/*! width: 31vh; width: 45%;*/text-align: center;">
                    <img src="/Install/demo/display/img/brand/logo.png" style="height: 5.5vh;">
                    <div class="menu">
                        <div><a href="<?=Pages::about?>"><?=$App->Translater(array('RU'=>'О проекте', 'EN'=>'About'))?></a></div>
                        <div><a href="<?=Pages::main?>"><?=$App->Translater(array('RU'=>'Главная', 'EN'=>'Main page'))?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>