<?
/**
 * @var \app\WebUIApplication $App
 */
use app\display\nodes\menu\LanguageMenu;
use app\display\nodes\menu\TopMenuGen;
use app\pages\Page;
use app\pages\Pages;

$App = $this->Application();

/**
 * @var $this Page
 */
?>
<div class="content-wrapper">
    <div class="w-form-done">
        <div><?=$App->Translater(array(
                'RU'=>'Приветствую вас на главной странице демонстрационного сайта на базе KORtx Bee'
            , 'EN'=>'Glad to meet you on this main page of the KORtx Bee based demo-site'
            ))?>.</div>
    </div>
    <section>
    <div class="container-fluid main-page">
        <div class="row">
        <div class="container-top">
            <div class="about-author">
                <h3><?=$App->Translater(array('RU'=>'Об авторе (обо мне, то есть)', 'EN'=>'About author (means about myself)'))?>.</h3>
                <?
                $authorTxtBlock = $App->TryGetTranslated('about.php');
                if($authorTxtBlock) require_once $authorTxtBlock;
                ?>
            </div>
            <div class="tab-wrapper row no-gutters history">
                <div class="tab-content col-lg-12">
                    <div class="one-tr">
                        <h2><?=$App->Translater(array('RU'=>'Краткая история развития KORtx Bee', 'EN'=>'Some sketchy history of KORtx Bee development'))?></h2>
                        <div class="scroll-y">
                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2022</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Россия, Калининград', 'EN'=>'Kaliningrad, Russia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Cоздание установщика и наглядного микросайта для технической демонстрации возможностей фреймворка KORtx Bee.
                                                                        Публикация.'
                                                                 , 'EN'=>'Make of an installer and a representative microsite for the technical demonstration of KORtx Bee framework capabilities.
                                                                        Publication.'))?>

                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/22/6.jpg" />
                                </div>
                            </div>
                            <div class="row divider">
                                <div>
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>


                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2021</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Россия, Сочи', 'EN'=>'Sochi, Russia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Отсоединение и оформление некоторой базовой части существующих решений в проект KORtx Bee.
                                                                        Создание описания фреймворка с целью дальнейшей публикации.'
                                                                    , 'EN'=>'Extraction and arrangement for some basic part of the framework\'s existing solutions in the global project KORtx Bee.
                                                                    Preparation of description for the purpose of further publication.'))?>
                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/21/5.jpg" />
                                </div>
                            </div>
                            <div class="row divider">
                                <div>
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>


                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2018</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Абхазия, озеро Рица', 'EN'=>'Lake Ritsa, Abkhazia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Первый опыт реализации масштабного проекта на базе собственного фреймворка (примерно 100 видов, около 20 разных моделей-описателей,
                                                                        разнообразие связей, поддержка нескольких языков, интеграция с аналитикой, разнообразный внутренний функционал от файлового менеджера
                                                                        до рассылки и чатов, API), попытка уйти от неродившегося и потерявшего актуальность долгостроя на Symfony 3. Проект реализован,
                                                                        однако по ряду причин так и не был запущен в коммерческую эксплуатацию.'
                                                                    , 'EN'=>'The first huge experience of implementing a large-scale project based on this framework (about 100 views, near 20 different
                                                                        description models, a variety of relations, multilanguage setup, integration with analytics, many different internal
                                                                        functions - from a file manager to mailing lists, chats and API), an attempt to get away from the unborn and long-term
                                                                        construction on Symfony 3 that had lost it\'s relevance. The project was implemented, but for a number of reasons
                                                                        it was never put into commercial operation.'))?>
                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/18/4.jpg" />
                                </div>
                            </div>
                            <div class="row divider">
                                <div>
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>


                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2015</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Россия, Плёс', 'EN'=>'Plyos, Russia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Прототип KORtx Bee использовался для реализации микросервиса для управления заявками с функциями отчётности.
                                                                        Вместе с тем был добавлен провайдер MySQL, изменён принцип описания моделей, реализовано логирование. Работало решение примерно 2 года.'
                                                                    , 'EN'=>'KORtx Bee prototype was used to implement a ticket management microservice with reporting features.
                                                                    With that, MySQL provider had been developed, principle of describing models had been changed, logging had been added. The solution worked for about 2 years.'))?>
                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/15/3.JPG" />
                                </div>
                            </div>
                            <div class="row divider">
                                <div>
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>


                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2014</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Россия, Гороховец', 'EN'=>'Gorokhovets, Russia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Первая структурная наработка, реализующая службу отслеживания источников телефонных звонков. Сделано описание утилитных методов,
                                                                    использующихся в ядре, провайдер PostgreSQL, механизмы отображения. По сути, первый proof of concept. Проработал около полугода
                                                                    для двух маленьких местных турфирм, но не заинтересовал пользователей.'
                                                                    , 'EN'=>'The first core structure, a calltracking service. Created core utility methods, PostgreSQL provider, view mechanics.
                                                                    May be considered as the first proof of concept. Service based on this framework worked for half a year
                                                                    for two small travel agencies, but did not cause interest.'))?>
                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/14/2.jpg" />
                                </div>
                            </div>
                            <div class="row divider">
                                <div>
                                    <i class="fas fa-angle-up"></i>
                                </div>
                            </div>


                            <div class="row">
                                <h3><img src="/Install/demo/display/img/icons/date.png" />2013</h3>
                                <div class="text-year">
                                    <span class="location"><img src="/Install/demo/display/img/icons/map.png">
                                        <?=$App->Translater(array('RU'=>'Россия, Москва', 'EN'=>'Moscow, Russia'))?>
                                    </span><br>
                                    <span class="event"><img src="/Install/demo/display/img/icons/head.png">
                                        <?=$App->Translater(array('RU'=>'Возникла идея о создании Фреймворка, на котором будет удобно писать сайты без необходимости перелопачивания тонн текста статей,
                                                                    прохождения спец. курсов "погружения в...", с интуитивно понятной структурой и функционалом. Созданы наброски движка.'
                                                                , 'EN'=>'The idea come and stayed to make a Framework that would be so easy to use in development,
                                                                    without tons of articles to read and lots of these courses "in the deeps...". The Framework that would be
                                                                     intuitive across it\'s structure and functionality. Made up some drafts of this engine.'))?>

                                    </span>
                                </div>
                                <div class="item__img img-year">
                                    <img src="/Install/demo/display/img/history/13/1.jpg" />
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="thank-you-guys">
                    <h4>
                        <?=$App->Translater(array(
                                'EN'=>'My gratitude for people, who made up some nice and cool things that
                                        are being used in this demo:'
                                , 'RU'=>'Мои благодарности людям, сделавшим красивые клёвые штуки,
                                        которые используются в данной демонстрации:'))?>

                    </h4>
                    <div class="col-lg-12">
                        Jakub Vrana - <?=$App->Translater(array('EN'=>'for', 'RU'=>'за'))?> Adminer.
                    </div>
                    <div class="col-lg-12">
                        Kruglov Sergei, Dmitriy Zayceff - <?=$App->Translater(array('EN'=>'for', 'RU'=>'за'))?> KCaptcha.
                    </div>
                    <div class="col-lg-12">
                        Jared Reich - <?=$App->Translater(array('EN'=>'for', 'RU'=>'за'))?> Pell.
                    </div>
                    <div class="col-lg-12">
                        Rodrigo Brito - <?=$App->Translater(array('EN'=>'for', 'RU'=>'за'))?> Paginator.
                    </div>
                </div>
            </div>

        </div>
        </div>
    </div>
    </section>
</div>
<script type="text/javascript">
    $(window).load(
        function(){
            var messageBody = document.querySelector('.history div.scroll-y');
            messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
        }
    );
</script>
