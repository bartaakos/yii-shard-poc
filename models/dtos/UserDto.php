<?php

/**
 * @property string $id
 * @property string $name
 * @property integer $status
 * @property string $email
 * @property string $reminder_hash
 * @property string $last_login_time
 * @property string $create_time
 * @property string $update_time
 * @property string $description
 * @property string[] $blobs
 */
class UserDto extends BaseDto
{
    public $id;
    public $name;
    public $status;
    public $email;
    public $reminder_hash;
    public $last_login_time;
    public $create_time;
    public $update_time;
    public $description;
    public $blobs;

    protected static $safeAttributes = array('id', 'name', 'status', 'email', 'reminder_hash', 'last_login_time', 'create_time', 'update_time', 'description', 'blobs');
}
