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

### Configuration 

All db instance ids (not database names) used throughout the application:

```php
class DbComponents
{
    const MainDb = 'db';
    const SharedDb1 = 'lpdbtest_shared_1';
    const SharedDb2 = 'lpdbtest_shared_2';
    
    static $componentIds = array(self:: MainDb, self::SharedDb1, self::SharedDb2);
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
        DbComponents::SharedDb1 => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_shared_1',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        DbComponents::SharedDb2 => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=lpdbtest_shared_2',
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

Returns the requested db connection if it's available or the main db connection otherwise. If you haven't specified the shared db-s in the <code>custom.php</code> then it won't find them so you get the main db connection where you keep all your data in this case.

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

### SharedActiveRecord

Lets you change the db under an AR Model

```php
class SharedActiveRecord extends GxActiveRecord
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

e.g. the UserDetails model's db should be on the SharedDb1 (lpdbtest_shared_1) shard:

```php
abstract class BaseUserDetails extends SharedActiveRecord { /* ... */ }
```

```php
class UserDetails extends BaseUserDetails
{
	protected $dbComponentId = DbComponents::SharedDb1;
  // ...
}
```
