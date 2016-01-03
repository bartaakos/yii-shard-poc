<?php

/**
 * This is the model base class for the table "user".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "User".
 *
 * Columns in table "user" available as properties of the model,
 * and there are no model relations.
 *
 * @property string $id
 * @property string $name
 * @property integer $status
 * @property string $email
 * @property string $password
 * @property string $reminder_hash
 * @property string $last_login_time
 * @property string $create_time
 * @property string $update_time
 *
 */
abstract class BaseUser extends SharedActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'user';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'User|Users', $n);
	}

	public static function representingColumn() {
		return 'email';
	}

	public function rules() {
		return array(
			array('status, email', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name, email, password, reminder_hash', 'length', 'max'=>255),
			array('last_login_time, create_time, update_time', 'safe'),
			array('name, password, reminder_hash, last_login_time, create_time, update_time', 'default', 'setOnEmpty' => true, 'value' => null),
			array('id, name, status, email, password, reminder_hash, last_login_time, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Name'),
			'status' => Yii::t('app', 'Status'),
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'reminder_hash' => Yii::t('app', 'Reminder Hash'),
			'last_login_time' => Yii::t('app', 'Last Login Time'),
			'create_time' => Yii::t('app', 'Create Time'),
			'update_time' => Yii::t('app', 'Update Time'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('reminder_hash', $this->reminder_hash, true);
		$criteria->compare('last_login_time', $this->last_login_time, true);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('update_time', $this->update_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}