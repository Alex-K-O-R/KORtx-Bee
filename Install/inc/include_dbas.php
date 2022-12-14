<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/constants/DBChanges.php');
include_once($rootCrPath.'/dba/DBModificationContext.php');
include_once($rootCrPath.'/dba/Page.php');
include_once($rootCrPath.'/dba/classes/LogDBA.php');
include_once($rootCrPath.'/utilities/Array_.php');
include_once($rootCrPath.'/dba/classes/_SecurityDBA.php');
include_once($rootCrPath.'/dba/classes/_UserDBA.php');
include_once($rootCrPath.'/models/inner/DynamicsModelContainer.php');
include_once($rootCrPath.'/models/inner/SecurityMDL.php');
include_once($rootCrPath.'/models/_UserMDL.php');
include_once($rootCrPath.'/utilities/MathEtc.php');
include_once($rootCrPath.'/utilities/DataConversion.php');

include_once($usrCrPath.'/dba/classes/UserDBA.php');
include_once($usrCrPath.'/models/UserMDL.php');
?>