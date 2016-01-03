<?php

class DbManager
{
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
}