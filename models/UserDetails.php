<?php

Yii::import('application.models._base.BaseUserDetails');

class UserDetails extends BaseUserDetails
{
	protected $dbComponentId = DbComponents::SharedDb1;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}