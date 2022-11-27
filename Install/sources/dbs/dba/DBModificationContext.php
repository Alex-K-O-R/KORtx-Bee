<?php
namespace app\dba;
use app\dba\constants\DBChanges;

class DBModificationContext {
    private $targetEntId;
    /**
     * @var DBAccess|IBeeDBA $DBA
     */
    private $DBA;
    private $sourceId;
    private $operationType;

    /**
     * @param $sourceId
     * @param $targetEntId
     * @param $usedDBA DBAccess|IBeeDBA
     * @param string $operationType
     */
    function __construct($sourceId, $targetEntId, $usedDBA, $operationType = DBChanges::manual)
    {
        $this->targetEntId = intval($targetEntId);
        $this->DBA = $usedDBA;
        $this->operationType = $operationType;
        $this->sourceId = intval($sourceId);
    }

    public function getOperationType()
    {
        return $this->operationType;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function getTargetEntId()
    {
        return $this->targetEntId;
    }

    public function getTargetEntType()
    {
        return $this->DBA->EntityCode();
    }

    /**
     * @return DBAccess|IBeeDBA
     */
    public function getUsedDba()
    {
        return $this->DBA;
    }

    public function changeTargetEntIdTo($targetEntId)
    {
        $this->targetEntId = $targetEntId;
        return $this;
    }
}