<?php
namespace app\dba\inners;

use app\dba\DBAccess;
use app\dba\constants\DBSettings;
use app\dba\DBModificationContext;
use app\dba\constants\DBChanges;
use app\utilities\inner\CIE;

class LogDBA {
    const table = DBSettings::dbprfx.'_log';

    /**
     * @param DBAccess $DBA
     * @param $entId
     * @param $entType
     * @param $prev_val
     * @param $curr_val
     * @param $subjTrace
     * @param string $actionDesc
     * @param string $srcType
     * @param string $priority
     * @return bool|null
     */
    public static function logSystemAction($DBA, $entId, $entType, $prev_val, $curr_val, $subjTrace, $actionDesc = "", $srcType="", $priority = DBChanges::level_low) {
            if ($DBA && ((!empty($entId))||(!empty($entType)))) {
                $actionDesc = CIE::l($actionDesc);
                if(is_array($prev_val)) $prev_val = print_r($prev_val, true);
                if(is_array($curr_val)) $curr_val = print_r($curr_val, true);
                $prev_val = $DBA->escape_string(CIE::l($prev_val));
                $curr_val = $DBA->escape_string(CIE::l($curr_val));
                $subjTrace = ($subjTrace===null)?'NULL':CIE::l($subjTrace);
                $priority = CIE::l($priority);
                $srcType = CIE::l($srcType);

                $qry = 'INSERT INTO '.self::table.'(
                entity_id, entity_type, subject_trace, action, source_type, priority, old_value, new_value)'.
                        ' values (\''.$entId.'\',\''.$entType.'\',  \''.$subjTrace.'\',
                         \''.$actionDesc.'\',\''.$srcType.'\','.
                        '\''.$priority.'\', \''.$prev_val.'\',\''.$curr_val.'\')';
                if (!$DBA->query($qry)) return null;
                return true;
        } else return null;
    }


    /**
     * @param DBModificationContext $ModificationContext
     * @param $prev_val
     * @param $curr_val
     * @param string $actionDesc
     * @param string $priority
     * @return bool|null
     */
    public static function logUserAction($ModificationContext, $prev_val, $curr_val, $actionDesc = "", $priority = DBChanges::level_low) {
        if ((!empty($ModificationContext->getTargetEntId()))||(!empty($ModificationContext->getTargetEntType()))) {
            $actionDesc = CIE::l($actionDesc);
            if(is_array($prev_val)) $prev_val = print_r($prev_val, true);
            if(is_array($curr_val)) $curr_val = print_r($curr_val, true);
            $prev_val = $ModificationContext->getUsedDba()->escape_string(CIE::l($prev_val));
            $curr_val = $ModificationContext->getUsedDba()->escape_string(CIE::l($curr_val));
            $priority = CIE::l($priority);
            $srcType = CIE::l($ModificationContext->getOperationType());

            $qry = 'INSERT INTO '.self::table.'(
                entity_id, entity_type, subject_trace, subject_id, action, source_type, priority, old_value, new_value)'.
                ' values (\''.$ModificationContext->getTargetEntId().'\',\''.$ModificationContext->getTargetEntType().'\', \''.$_SERVER['REMOTE_ADDR'].'\', '.$ModificationContext->getSourceId().',
                         \''.$actionDesc.'\',\''.$srcType.'\','.
                '\''.$priority.'\', \''.$prev_val.'\',\''.$curr_val.'\')';
            if (!$ModificationContext->getUsedDba()->query($qry)) return null;
            return true;
        } else return null;
    }


    /** Returns modification info about entity that was changed
     * @param DBAccess $DBA
     * @param $entType
     * @param $entId
     * @param int $limit
     * @return resource
     */
    public function readChangesForEntityByTypeId($DBA, $entType ,$entId, $limit = 100){
        if($DBA){
            $entType = CIE::l($entType);
            $entId = intval($entId);
            $limit = intval($limit);
            return $DBA->query('SELECT * FROM '.LogDBA::table.' WHERE entity_type = \''.$entType.'\' AND entity_id = '.$entId.' LIMIT '.$limit.' ORDER BY category, log_id DESC', 'arr');
        } else return false;
    }


    /** Returns all changes made by some user
     * @param DBAccess $DBA
     * @param $srcId
     * @param int $limit
     * @return resource
     */
    public function readLastChanges($DBA, $srcId, $limit = 100){
        if($DBA){
            $srcId = intval($srcId);
            return pg_query('SELECT DISTINCT ON(action) * FROM
                    '.LogDBA::table.' WHERE subject_id = '.$srcId.' LIMIT '.$limit.' ORDER BY category, log_id DESC', 'arr');
        } else return false;
    }

}