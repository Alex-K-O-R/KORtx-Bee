<?php
$Ted = $UsrDba->getUserInfoByLogin('Ted');

if(!$Ted){
    $Ted = $UsrDba->addUser('Ted', '1234', $DBModCntxt);
    $Ted = $UsrDba->getUsersInfoesByUsersIds(1,0,null, $Ted);
    if($Ted) $Ted = $Ted[0];
}

$Ted = new \app\models\UserMDL($Ted);

//EN
$UsrDba->updateName($Ted->getUserId(), 'Teodor', $EN_lang_id, $DBModCntxt);
$UsrDba->updateSurname($Ted->getUserId(), 'Rofleston', $EN_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Ted->getUserId(), 'Professional restaurant critique', $EN_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Ted->getUserId(), "My 2 credos: *gotta taste it all* and *cook with love and you'd beloved*.", $EN_lang_id, $DBModCntxt);
$about = "Teodor Rofleston. No need to introduce for restaurant business' segment. Cookin'World magazine's best reviewer of the years 2012-2014, 2019, 2021.\r\n";
$about .= "I've examined food and found some masterpieces in almost 300 restaurants around the globe. Listen to me, read me and you won't be fooled.\r\n";
$about .= "Give master-chief a good fish, and you'll save him 7 pounds, give him a sharp advice instead of a good fish and he will surely remember ya forever! \r\n";
$UsrDba->updateAdditionalInfo($Ted->getUserId(), $about, $EN_lang_id, $DBModCntxt);
//RU
$UsrDba->updateName($Ted->getUserId(), 'Теодор', $RU_lang_id, $DBModCntxt);
$UsrDba->updateSurname($Ted->getUserId(), 'Рофлстоун', $RU_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Ted->getUserId(), 'Профессиональный ресторанный критик', $RU_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Ted->getUserId(), "Мои 2 кредо: *нужно всё попробовать* и *готовь с любовью да и слюбишься*.", $RU_lang_id, $DBModCntxt);
$about = "Теодор Рофлстоун. Не нуждаюсь в представлении для ресторанного бизнеса. Лучший обозреватель издания Cookin'World magazine с 2012 по 2014 год, а также в 2019, 2021 годах.\r\n";
$about .= "Я пробовал еду и открывал редкие шедевры в 300 ресторанах мира. Слушай меня, читай меня и тебя не смогут обмануть.";
$about .= "Дай шефу хорошую рыбу, и ты сэкономишь ему немного денег. Дай ему меткий совет вместо хорошей рыбы, и он точно тебя запомнит навсегда! \r\n";
$UsrDba->updateAdditionalInfo($Ted->getUserId(), $about, $RU_lang_id, $DBModCntxt);

$UsrDba->updateUserAvatarPath($Ted, $InstallationSourcePath.'/inc/demo/avatars/Avatar_b1.png', $DBModCntxt);
?>