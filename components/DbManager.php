<?php

class DbManager
{
    /**
     * @param string $id Db instance id
     * @return CDbConnection
     */
    public static function getDb($id)
    {
        if(self::isComponentAvailable($id)) {
            $component = Yii::app()->getComponent($id);

            if($component && $component instanceof CDbConnection) {
                return $component;
            }
        }

        return Yii::app()->getDb();
    }

    private static function isComponentAvailable($id)
    {
        return in_array($id, DbComponents::$componentIds) && Yii::app()->hasComponent($id);
    }

    /**
     * Allows switching between duplicated shards based on a user's properties
     * (in this implementation the only thing we check is the user id)
     *
     * @param int $userId
     * @param string|null $baseDbComponentId
     * @return CDbConnection
     */
    public static function getUserShard($userId, $baseDbComponentId = null)
    {
        // in some cases it's needed to know what would be the original db for the request
        if($baseDbComponentId == DbComponents::ShardDb1) {
            // e.g. we use 2 dbs for the ShardDb1 for load balancing purposes
            // here we can define a logic for that
            // as a simple example we redirect every 2nd user (based on their id) to the US db

            if($userId % 2 == 0) {
                return self::getDb(DbComponents::ShardDb1US);
            } else {
                return self::getDb(DbComponents::ShardDb1);
            }
        }

        // main db
        // as a simple example we redirect every 2nd user (based on their id) to the US db
        if($userId % 2 == 0) {
            return self::getDb(DbComponents::MainDbUS);
        }

        return self::getDb(DbComponents::MainDb);
    }
}