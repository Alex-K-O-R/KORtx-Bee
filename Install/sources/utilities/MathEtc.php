<?php

namespace app\utilities\inner;


class MathEtc {
    public static function generateRandomScrtCnsqnc($str, $resultLength = 7){
        $tryout = mb_strtolower((hash('md5', $str)));
        if (preg_match('/[a-z]/', $tryout, $match)) {
            $firstLetterPos = strpos($tryout,$match[0]);
            if($firstLetterPos<(strlen($tryout)/2)){
                return mb_substr($tryout, $firstLetterPos, $resultLength);
            } else {
                return array_reverse(mb_substr($tryout, $firstLetterPos, -1*$resultLength));
            }
        }
        return $str;
    }

    public static function randomlyUppercaseLetters($str, $sourceIsAlreadyLowerCase = true){
        if($str===null) {return null;}
        if(!$sourceIsAlreadyLowerCase){$str = mb_strtolower($str);}
        for($i=1;$i<mb_strlen($str);$i++){
            if(!is_numeric($str[$i])){
                if(rand(0,9) % 2 === 0){$str[$i] = mb_strtoupper($str[$i]);}
            }
        }
        return $str;
    }
}