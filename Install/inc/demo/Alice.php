<?php
$Alice = $UsrDba->getUserInfoByLogin('Alice');

if(!$Alice){
    $Alice = $UsrDba->addUser('Alice', '1234', $DBModCntxt);
    $Alice = $UsrDba->getUsersInfoesByUsersIds(1,0,null, $Alice);
    if($Alice) $Alice = $Alice[0];
}
$Alice = new \app\models\UserMDL($Alice);

//EN
$UsrDba->updateName($Alice->getUserId(), 'Alice', $EN_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Alice->getUserId(), 'Psychologist, writer, astrologist, philosopher', $EN_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Alice->getUserId(), "Your kind neighbour, Alice.", $EN_lang_id, $DBModCntxt);
$about = "You don't know why am I leaving. Or where I'm gonna go.\r\n";
$about .= "You see, I've got my reasons, but you just don't want to know.\r\n";
$about .= "Cause for 28 years you've never ever mentioned Alice... \r\n";
$UsrDba->updateAdditionalInfo($Alice->getUserId(), $about, $EN_lang_id, $DBModCntxt);
//RU
$UsrDba->updateName($Alice->getUserId(), 'Элис', $RU_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Alice->getUserId(), 'Психолог, писатель, астролог, философ', $RU_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Alice->getUserId(), "Твоя дружелюбная соседка.", $RU_lang_id, $DBModCntxt);
$about = "Не знаешь ты зачем и почему я ухожу.\r\n";
$about .= "На то ведь есть причины, но тебе их не скажу.\r\n";
$about .= "Ведь все 28 лет подряд не видел ты девчонку Элис...\r\n";
$UsrDba->updateAdditionalInfo($Alice->getUserId(), $about, $RU_lang_id, $DBModCntxt);

$UsrDba->updateUserAvatarPath($Alice, $InstallationSourcePath.'/inc/demo/avatars/Avatar_g2.png', $DBModCntxt);
?>