<?php
namespace app\utilities\inner;

use app\Application;
use app\utilities\inner\Currency;

class Text_ {

    public static function text_correct($text)
    {
        $text=nl2br($text);
        $text=str_replace('<br/>', '<br />', $text);
        $text=str_replace('<br>', '<br />', $text);
        /**/$text=str_replace('<br />
        <br />', '</p>
        <p class="k-text">', $text);
        $text=str_replace('<br /><br />', '</p>
        <p class="k-text">', $text);
        $text=str_replace('$ ', ' USD ', $text);
        $text=str_replace(' м?', ' кв.м.', $text);
        $text=str_replace('\\"', '\"', $text);
        $text=str_replace(' \"', ' &laquo;', $text);
        $text=str_replace('\" ', '&raquo; ', $text);
        $text=str_replace(' - ', ' &mdash; ', $text);
        $text=str_replace('\",', '&raquo;,', $text);
        $text=str_replace('.\"', '&raquo;.', $text);
        $text=str_replace('\".', '&raquo;.', $text);
        $text=str_replace('!\"', '!&raquo;', $text);
        $text=str_replace('?\"', '?&raquo;', $text);
        $text=str_replace('">
        \"', '">
        &laquo;', $text);
        $text=str_replace('\'', '`', $text);
        return ('<p class="k-text">'.$text.'</p>');
    }


    public static function processValueRussianDays($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'день';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'дня';}
        return 'дней';
    }

    public static function processValueRussianViews($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'просмотр';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'просмотра';}
        return 'просмотров';
    }

    public static function processValueRussianVisitors($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'посетитель';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'посетителя';}
        return 'посетителей';
    }

    /** TODO: сделать общий метод для суффиксов -ов -- сделано, переделать остальные, если подходит
     * @param $intVal
     * @param string $lang_acronym
     * @return string
     */
    public static function processValueRussianParticipants($intVal, $lang_acronym=Application::DEFAULT_LANGUAGE_ACRONYM)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return Application::GlobalTransliter(array('RU'=>'участник', 'EN'=>'participant'), $lang_acronym);}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return Application::GlobalTransliter(array('RU'=>'участника', 'EN'=>'participants'), $lang_acronym);}
        return Application::GlobalTransliter(array('RU'=>'участников', 'EN'=>'participants'), $lang_acronym);
    }


    public static function processValuePostsInBlog($intVal, $lang_acronym=Application::DEFAULT_LANGUAGE_ACRONYM)
    {
        return self::processValueWithSuffix_ov($intVal, Application::GlobalTransliter(array('RU'=>'пост', 'EN'=>'post'), $lang_acronym), $lang_acronym);
    }

    private static function processValueWithSuffix_ov($intVal, $root, $lang_acronym=Application::DEFAULT_LANGUAGE_ACRONYM)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return $root;}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return $root.Application::GlobalTransliter(array('RU'=>'а', 'EN'=>'s'), $lang_acronym);}
        return $root.Application::GlobalTransliter(array('RU'=>'ов', 'EN'=>'s'), $lang_acronym);
    }

    public static function processValueRussianOffers($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'предложение';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'предложения';}
        return 'предложений';
    }


    public static function processValueRussianCampaigns($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'акция';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'акции';}
        return 'акций';
    }

    public static function processValueRussianNews($intVal)
    {
        $intVal = strval($intVal);
        if(Array_::endsWith($intVal, 1) && !Array_::endsWith($intVal, 11)){return 'новость';}
        if(Array_::endsWith($intVal, 2) && !Array_::endsWith($intVal, 12)||Array_::endsWith($intVal, 3) && !Array_::endsWith($intVal, 13)||Array_::endsWith($intVal, 4) && !Array_::endsWith($intVal, 14)){return 'новости';}
        return 'новостей';
    }

    public static function Translit($string)
    {
        $table = array(
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'YO',
            'Ж' => 'ZH',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'N' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'CH',
            'Ш' => 'SH',
            'Щ' => 'SCH',
            'Ь' => '',
            'Ы' => 'Y',
            'Ъ' => '',
            'Э' => 'E',
            'Ю' => 'YU',
            'Я' => 'YA',

            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
        );

        $output = str_replace(
            array_keys($table),
            array_values($table),trim($string)
        );

        return $output;
    }

    /*
     *     function ru2Lat($string)
    {
    $rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
    $lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
    $string = str_replace($rus,$lat,$string);
    $string = strtr($string,
         "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ",
         "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");
    return($string);

    }


    function transl($st,$code='utf-8'){

$st = mb_strtolower($st, $code);
$st = str_replace(array('?','!','.',',',':',';','*','(',')','{','}','%','#','№','@','$','^','-','+','/','\\','=','|','"','\'','а','б','в','г','д','е','ё','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ъ','ы','э',' ','ж','ц','ч','ш','щ','ь','ю','я'), array('','','','','','','','','','','','','','','','','','','','','','','','','',
    //remove bad chars
'a','b','v','g','d','e','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','j','i','e','_','zh','ts','ch','sh','shch','','yu','ya'), $st);return $st;}


echo transl("Какаято не реальная строка @$^*^&()&(5");
     */

    public static function removeParasiteWordFromBeginning($str, $word = 'лот'){
        $tmp = mb_strtolower($str);
        $tmp = ltrim($tmp);
        if(mb_strpos($tmp, $word)===0){
            return self::mb_ucfirst(mb_substr(
                    $str,
                    (mb_strlen($str)-mb_strlen($tmp)+1)+mb_strlen($word))
            );
        } else return $str;
    }

    public static function mb_ucfirst($str){
        if(!$str || $str === null) return $str;
        return mb_strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1);
    }



    public static function periodDats($start, $stop, $lang_acronym=Application::DEFAULT_LANGUAGE_ACRONYM){
        if($start == null) return ''; /*TODO: проверить, отсутствие какой границы (левой, или правой) может быть допустимо */
        $weekday = Application::GlobalTransliter(array(
                'RU'=>array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'),
                'EN'=>array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'))
            , $lang_acronym);
        $start = strtotime($start);
        $stop = strtotime($stop);
        $m_start = date('n', $start);
        $m_stop = date('n', $stop);
        $y_start = ' '.date('Y', $start);
        $y_stop = ' '.date('Y', $stop);
        if($m_start != $m_stop || $y_start != $y_stop) {
            $m_name_start = ' '.$weekday[intval($m_start)];
            $m_name_stop = ' '.$weekday[intval($m_stop)];
        }
        else{
            $m_name_start = '';
            $m_name_stop = ' '.$weekday[$m_stop];
        }
        if($y_start == $y_stop)$y_start = '';
        $result = ((date('m.d', $start)!=date('m.d', $stop))?date('j', $start).$m_name_start.$y_start.'-':'').date('j', $stop).$m_name_stop.$y_stop;
        return $result;
    }


    /**
     * Hide email address with asterisk(*)
     *
     * @param    $email
     * @return  $email with asterisk(*) // t***********l@g*****l.com
     *
     */
    static function hideEmail($email)
    {
        $mail_segments = explode("@", $email);
        $mail_segments[0] = substr($mail_segments[0], 0, 1) . str_repeat("*", 6) . substr($mail_segments[0], -2);
        $pos = strpos($mail_segments[1], '.');
        $mail_segments[1] = /*substr($mail_segments[1], 0, 1) .*/ str_repeat("*", 4) . substr($mail_segments[1], $pos);
        return implode("@", $mail_segments);
    }


    /**
     * @param $text string
     * @param $visual boolean
     * @return string
     */
    public static  function currentURLsait($text, $visual=false){//Обработка URL адреса сайтов. Если нужно удаляет протоколы или подготавливает к корректному переходу.
        if($text!='' && CIE::l($text)) {
            $text = str_replace('\\', '/', mb_strtolower($text));
            $text = explode("/", $text);
            if (count($text) > 2 && $text[1] == '') {
                if ($text[0] != 'https:') $text[0] = 'http:';
                if ($visual) $text = array_slice($text, 2);
            } else return (($visual) ? '' : 'http://') . implode('/', $text);
            return implode('/', $text);
        }
        else return ((intval($text)===$text)?'':$text);
    }


    public static function getPhone($lang = null, $format = null){
        return false;
        //return "8 800 222 02 65"; "1 3 3 2 2";
    }



    public static function getCurrencyShortNameByLang($currId, $lang_acronym=Application::DEFAULT_LANGUAGE_ACRONYM){
        switch ($currId){
            case Currency::USD:{
                return Application::GlobalTransliter(array('RU'=>'долл.', 'EN'=>'$'), $lang_acronym);
            } break;
            case Currency::RUB:{
                return Application::GlobalTransliter(array('RU'=>'руб.', 'EN'=>'RUB'), $lang_acronym);
            } break;
            case Currency::EUR:{
                return Application::GlobalTransliter(array('RU'=>'евр.', 'EN'=>'EUR'), $lang_acronym);
            } break;
            case Currency::AED:{
                return Application::GlobalTransliter(array('RU'=>'дирх.', 'EN'=>'AED'), $lang_acronym);
            } break;
        }
    }
}

