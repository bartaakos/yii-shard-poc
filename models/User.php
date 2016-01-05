<?php

Yii::import('application.models._base.BaseUser');

class User extends BaseUser
{
    const STATUS_PENDING = 10;
    const STATUS_ACTIVE = 50;
    const STATUS_DELETED = 90;

    public $new_password, $new_password_repeat;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'timestamp' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
                'timestampExpression' => "date('Y-m-d H:i:s')",
            ),
            'statuses' => array(
                'class' => 'vendor.yiiext.status-behavior.EStatusBehavior',
                'statusField' => 'status',
                'statuses' => array(
                    self::STATUS_PENDING => Yii::t('app', 'Registered'),
                    self::STATUS_ACTIVE => Yii::t('app', 'Active'),
                    self::STATUS_DELETED => Yii::t('app', 'Inactive'),
                ),
            ),
        );
    }

    public function rules()
    {
        return array_merge(array(
            array('email, new_password, new_password_repeat', 'required', 'on' => array('insert')),
        ), parent::rules());
    }

//    skipped because of joins among different db connections
//    public function relations()
//    {
//        return array_merge(array(
//            'details' => array(self::HAS_ONE, 'UserDetails', 'user_id'),
//            'blobs' => array(self::HAS_MANY, 'UserBlob', 'user_id'),
//        ), parent::relations());
//    }

    /**
     * @param User $model
     * @return UserDto
     */
    public static function toDto(User $model) {
        /** @var UserDetails $details */
        $details = $model->getUserDetails();
        /** @var UserBlob[] $blobs */
        $blobs = $model->getUserBlobs();

        /** @var UserDto $userDto */
        $userDto = new UserDto($model->getAttributes());
        $userDto->description = $details ? $details->description : '';
        $userDto->blobs = array();
        foreach ($blobs as $blob) {
            $userDto->blobs[] = $blob->blob_b64;
        }

        return $userDto;
    }

    /**
     * @return UserDetails
     */
    private function getUserDetails()
    {
        return UserDetails::model()->findShardedDataByUserId($this->id, 'user_id');
    }

    /**
     * @return UserBlob[]
     */
    private function getUserBlobs()
    {
        return UserBlob::model()->findAllByAttributes(array('user_id' => $this->id));
    }

    /**
     * Encrypt user password
     *
     * @param $password
     * @param null $salt
     * @return string
     */
    public function encrypt($password, $salt = null)
    {
        return crypt(urlencode($password), $salt);
    }

    /**
     * Generate hash for user email validation
     *
     * @return string
     */
    public function createHash()
    {
        return md5('qwewe' . $this->slug . $this->create_time);
    }

    protected function beforeSave()
    {
        if ($this->new_password) {
            $this->password = $this->encrypt($this->new_password);
        }

        return parent::beforeSave();
    }
}