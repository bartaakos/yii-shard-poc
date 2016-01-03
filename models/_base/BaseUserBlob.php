<?php

/**
 * This is the model base class for the table "user_blob".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "UserBlob".
 *
 * Columns in table "user_blob" available as properties of the model,
 * and there are no model relations.
 *
 * @property string $id
 * @property string $user_id
 * @property string $blob_b64
 *
 */
abstract class BaseUserBlob extends SharedActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'user_blob';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'UserBlob|UserBlobs', $n);
	}

	public static function representingColumn() {
		return 'blob';
	}

	public function rules() {
		return array(
			array('user_id, blob', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('id, user_id, blob', 'safe', 'on'=>'search'),
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
			'user_id' => Yii::t('app', 'User'),
			'blob_b64' => Yii::t('app', 'Blob'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('user_id', $this->user_id, true);
		$criteria->compare('blob_b64', $this->blob, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}