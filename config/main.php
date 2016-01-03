<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
    array(
        'aliases' => array(
            'vendor' => 'application.vendor',
            'giix' => 'vendor.assisrafael.giix',
        ),
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'Yii Application',
        'sourceLanguage' => 'en_us',
        'language' => 'en',
        // preloading 'log' component
        'preload' => array('log'),
        // autoloading model and component classes
        'import' => array(
            'application.models.*',
            'application.models.forms.*',
            'application.models.dtos.*',
            'application.components.*',
            'vendor.assisrafael.giix.components.*',
            'ext.yii-mail.*',
            'ext.*',
        ),
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'asdasd',
                'generatorPaths' => array(
                    'vendor.assisrafael.giix.generators', // giix generators
                ),
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1'),
            ),
        ),
        // application components
        'components' => array(
            'coreMessages' => array(
                'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'messages',
            ),
            'session' => array(
                'timeout' => 60 * 60 * 24,
            ),
            'user' => array(
                'class' => 'MyWebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => false,
            ),
            // uncomment the following to enable URLs in path-format
            'mail' => array(
                'class' => 'ext.yii-mail.YiiMail',
                'transportType' => 'php',
                'viewPath' => 'application.views.mail',
                'logging' => true,
                'dryRun' => false
            ),
            'authManager' => array(
                'class' => 'CDbAuthManager',
                'connectionID' => 'db',
                'itemTable' => 'authitem',
                'itemChildTable' => 'authitemchild',
                'assignmentTable' => 'authassignment',
                'defaultRoles' => array('authenticated', 'guest'),
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'rules' => require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'url_rules.php')
            ),
            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'site/error',
            ),

        ),
        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params' => require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'params.php'),
    ),
    require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'custom.php')
);