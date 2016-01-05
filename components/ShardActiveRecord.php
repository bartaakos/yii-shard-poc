<?php

abstract class ShardActiveRecord extends GxActiveRecord
{
    /** @var string|null DbComponent where the data should be located */
    protected $dbComponentId = null;

    /** @var null|string User Id field to use for overridden user sharding */
    protected $shardUserId = null;

    public function getDbConnection()
    {
        if($this->shardUserId) {
            self::$db = DbManager::getUserShard($this->shardUserId, $this->dbComponentId);
        } elseif($this->dbComponentId) {
            self::$db = DbManager::getDb($this->dbComponentId);
        }

        return parent::getDbConnection();
    }

    public function findShardedDataByUserId($userId, $userIdField, $condition='', $params=array())
    {
        $this->shardUserId = $userId;

        return $this->findByAttributes(array($userIdField => $userId), $condition, $params);
    }

    public function findAllShardedDataByUserId($userId, $userIdField, $condition='', $params=array())
    {
        $this->shardUserId = $userId;

        return $this->findAllByAttributes(array($userIdField => $userId), $condition, $params);
    }
}