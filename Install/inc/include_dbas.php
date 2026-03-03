<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/constants/DBChanges.php');
require_once($rootCrPath.'/dba/DBModificationContext.php');
require_once($rootCrPath.'/dba/Page.php');
require_once($rootCrPath.'/dba/classes/LogDBA.php');
require_once($rootCrPath.'/utilities/Arrays.php');
require_once($rootCrPath.'/dba/classes/SecurityDBA.php');
require_once($rootCrPath.'/dba/classes/UserDBA.php');
require_once($rootCrPath.'/models/inner/DynamicsModelContainer.php');
require_once($rootCrPath.'/models/inner/SecurityMDL.php');
require_once($rootCrPath.'/models/UserMDL.php');
require_once($rootCrPath.'/utilities/MathEtc.php');
require_once($rootCrPath.'/utilities/DataConversion.php');

require_once($usrCrPath.'/dba/classes/UserDBA.php');
require_once($usrCrPath.'/models/UserMDL.php');
?>