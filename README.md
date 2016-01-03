# LP Db PoC

## Static files

<pre>
/web/assets
/vendor
/runtime
/config/custom.php
/config/params.php
</pre>

The files listed above are ignored and you should handle them as static content.

## Sharding

![Image of possible databases](https://github.com/bartaakos/yii-shard-poc/blob/master/data/lpdbpoc.png)

### Configuration 

All db instance ids (not database names) used throughout the application:

```php
class DbComponents
{
    const MainDb = 'db';
    const ShardDb1 = 'lpdbtest_shard_1';
    const ShardDb2 = 'lpdbtest_shard_2';
    
    static $componentIds = array(self:: MainDb, self::ShardDb1, self::ShardDb2);
}
```

*(separated class because it is included before the autoload in order to keep it isolated)*

Configure them in the static <code>custom.php</code>:

```php
// ...
    'components' => array(
        DbComponents::MainDb => array(
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        DbComponents::ShardDb1 => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_shard_1',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        DbComponents::ShardDb2 => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_shard_2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
// ...
```

You might have only 1 db (no sharding):

```php
// ...
    'components' => array(
        DbComponents::MainDb => array(
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_full',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
// ...
```

### DbManager

Returns the requested db connection if it's available or the main db connection otherwise. If you haven't specified the shard db-s in the <code>custom.php</code> then it won't find them so you get the main db connection where you keep all your data in this case.

```php
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
}
```

### ShardActiveRecord

Lets you change the db under an AR Model

```php
class ShardActiveRecord extends GxActiveRecord
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
```

e.g. the UserDetails model's db should be on the ShardDb1 (lpdbtest_shard_1) shard:

```php
abstract class BaseUserDetails extends ShardActiveRecord { /* ... */ }
```

```php
class UserDetails extends BaseUserDetails
{
	protected $dbComponentId = DbComponents::ShardDb1;
  // ...
}
```

### Usage

There are certain functionalities in the application when you want to reach data that is possibly located in a shard database (e.g. getting user data with blobs - *for the sake of simplicity they are base64 encoded strings*)

**User::toDto()**

```php
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
        $userDto->description = $details->description;
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
        return UserDetails::model()->findByAttributes(array('user_id' => $this->id));
    }

    /**
     * @return UserBlob[]
     */
    private function getUserBlobs()
    {
        return UserBlob::model()->findAllByAttributes(array('user_id' => $this->id));
    }
```

Looking at the getters at the bottom you may say that there would be a better way to handle these kinds of connections among AR models (relations) but they cannot be used bacause they would do multi db joins:
```php
    public function relations()
    {
        return array_merge(array(
            'details' => array(self::HAS_ONE, 'UserDetails', 'user_id'),
            'blobs' => array(self::HAS_MANY, 'UserBlob', 'user_id'),
        ), parent::relations());
    }
```
This means that you **should NOT use relations** for reaching data that might be in another db.

**Writing queries**

Using the <code>DbManager</code> you can even get information from a certain shard regardless of where you are in the current context:

```php
        /** @var CDbConnection $db */
        $db = DbManager::getDb(DbComponents::ShardDb2);
        /** @var CDbCommand $cmd */
        $cmd = $db->createCommand("SELECT COUNT(id) FROM user_blob WHERE user_id = :user_id");

        $count = $cmd->queryScalar(array(':user_id' => 1));
```
