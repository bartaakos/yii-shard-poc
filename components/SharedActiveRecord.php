<?php

class SharedActiveRecord extends GxActiveRecord
{
    protected $dbComponentId = null;

    public function getDbConnection()
    {
        if($this->dbComponentId) {
            self::$db = DbManager::getDb($this->dbComponentId);
        }

        return parent::getDbConnection();
    }
}