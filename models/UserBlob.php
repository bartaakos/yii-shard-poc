<?php

Yii::import('application.models._base.BaseUserBlob');

class UserBlob extends BaseUserBlob
{
	protected $dbComponentId = DbComponents::SharedDb2;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}