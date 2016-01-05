# Yii DB Sharding PoC

## Static files

<pre>
/web/assets
/vendor
/runtime
/config/custom.php
/config/params.php
</pre>

The files listed above are ignored and you should handle them as static content.

## Install

- git clone
- composer update
- static files (create config files, add write permissions to the server for assets and runtime folders)

## Sharding

![Image of possible databases](https://github.com/bartaakos/yii-shard-poc/blob/master/data/lpdbpoc.png)

### Configuration 

All db instance ids (not database names) used throughout the application:

```php
class DbComponents
{
    const MainDb = 'db';
    const MainDbUS = 'db_us';
    const ShardDb1 = 'lpdbtest_shard_1';
    const ShardDb1US = 'lpdbtest_shard_1_us';
    const ShardDb2 = 'lpdbtest_shard_2';

    static $componentIds = array(self:: MainDb, self::MainDbUS, self::ShardDb1, self::ShardDb1US, self::ShardDb2);
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
        DbComponents::MainDbUS => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_us',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'asdasd',
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
        DbComponents::ShardDb1US => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_shard_1_us',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'asdasd',
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

By using segmented data (see below) we are able to define a certain logic to split the users/data among separate shards. This logic is implemented in the <code>getUserShard</code> function. For the PoC it's very simple and uses the user's id only.

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
```

### ShardActiveRecord

Lets you change the db under an AR Model

```php
class ShardActiveRecord extends GxActiveRecord
{
    /** @var string|null DbComponent where the data should be located */
    protected $dbComponentId = null;

    /** @var null|string User Id field to use for overridden user sharding */
    protected $shardUserId = null;

    public function getDbConnection()
    {
        if($this->shardUserId) {
            self::$db = DbManager::getUserShard($this->shardUserId, $this->dbComponentId);
        } elseif($this->dbComponentId) {
            self::$db = DbManager::getDb($this->dbComponentId);
        }

        return parent::getDbConnection();
    }

    public function findShardedDataByUserId($userId, $userIdField, $condition='', $params=array())
    {
        $this->shardUserId = $userId;

        return $this->findByAttributes(array($userIdField => $userId), $condition, $params);
    }

    public function findAllShardedDataByUserId($userId, $userIdField, $condition='', $params=array())
    {
        $this->shardUserId = $userId;

        return $this->findAllByAttributes(array($userIdField => $userId), $condition, $params);
    }
}
```

e.g. the UserBlob model's db should be on the ShardDb2 (lpdbtest_shard_2) shard:

```php
abstract class BaseUserBlob extends ShardActiveRecord { /* ... */ }
```

```php
class UserBlob extends BaseUserBlob
{
	protected $dbComponentId = DbComponents::ShardDb2;
  // ...
}
```

### Segmented data

By this I mean geographically segmented databases for instance where user1 is stored on db1 while user2 is stored on db2. For this in order to avoid collisions it's recommended to use Guids instead of auto increment ints for primary keys.

Segmented data (e.g. there are 2 main dbs that store the User model data) works a bit differently. There's no need to set the <code>$dbComponentId</code> in the model and you need to fetch the data with a specific helper function (<code>findShardedDataByUserId</code> and <code>findAllShardedDataByUserId</code>). Note: We fraction the data with a logic based on the user id. In the example it can be seen on the <code>User</code> and <code>UserDetails</code> models.

```php
$model = User::model()->findShardedDataByUserId($id, 'id');
```

### Usage

There are certain functionalities in the application when you want to reach data that is possibly located in a shard database (e.g. getting user data with blobs - *for the sake of simplicity they are base64 encoded strings*)

Note: There are 2 shard databases for storing UserDetails (segmented data): ShardDb1, ShardDb1US

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
```

Looking at the getters at the bottom you may say that there would be a better way to handle these kinds of connections among AR models (relations) but they cannot be used because they would do multi db joins:

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
