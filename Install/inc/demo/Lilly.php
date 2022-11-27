<?php
$Lillian = $UsrDba->getUserInfoByLogin('Lily');

if(!$Lillian){
    $Lillian = $UsrDba->addUser('Lily', '1234', $DBModCntxt);
    $Lillian = $UsrDba->getUsersInfoesByUsersIds(1,0,null, $Lillian);
    if($Lillian) $Lillian = $Lillian[0];
}
$Lillian = new \app\models\UserMDL($Lillian);

//EN
$UsrDba->updateName($Lillian->getUserId(), 'Lillian', $EN_lang_id, $DBModCntxt);
$UsrDba->updateSurname($Lillian->getUserId(), 'Jefferson', $EN_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Lillian->getUserId(), 'Computer Engineer (student)', $EN_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Lillian->getUserId(), 'Through a thorns to the stars!', $EN_lang_id, $DBModCntxt);
$about = "Hi. I'm Lilly. \r\n";
$about .= "I'm studying at the 4th grade of Computer Science department in Colorado State University. \r\n";
$about .= "All the way through the childhood I've found myself addicted to science and science fiction things.";
$about .= "Asimov Isaack, National Geographic and Star Trek were the favorites. \r\n";
$about .= "I'm sured that knowledge and studies would do the world a better place for a living! \r\n";
$UsrDba->updateAdditionalInfo($Lillian->getUserId(), $about, $EN_lang_id, $DBModCntxt);
//RU
$UsrDba->updateName($Lillian->getUserId(), 'Лиллиан', $RU_lang_id, $DBModCntxt);
$UsrDba->updateSurname($Lillian->getUserId(), 'Джефферсон', $RU_lang_id, $DBModCntxt);
$UsrDba->updateProfession($Lillian->getUserId(), 'Инженер ИВТ (учусь)', $RU_lang_id, $DBModCntxt);
$UsrDba->updateGeneralInfo($Lillian->getUserId(), 'Через тернии к звёздам!', $RU_lang_id, $DBModCntxt);
$about = "Привет, я - Лилли. \r\n";
$about .= "Учусь на 4 курсе в государственном институте Колорадо. Отделение компьютерных наук и естествознания.\r\n";
$about .= "Определённое притяжение к фантастике и науке у меня есть с раннего возраста.";
$about .= "Например, мне очень нравились труды Азимова Айзека, журналы NatGeo и сага Star Trek. \r\n";
$about .= "Я уверена, что познание и исследования определённо сделают Мир лучше! \r\n";
$UsrDba->updateAdditionalInfo($Lillian->getUserId(), $about, $RU_lang_id, $DBModCntxt);


$UsrDba->updateUserAvatarPath($Lillian, $InstallationSourcePath.'/inc/demo/avatars/Avatar_g1.png', $DBModCntxt);
?>