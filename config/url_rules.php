<?php
return array(
    '/' => 'site/index',

    array('user/list', 'pattern'=>'api/users', 'verb'=>'GET'),
    array('user/view', 'pattern'=>'api/users/<id:\d+>', 'verb'=>'GET'),
    array('user/update', 'pattern'=>'api/users/<id:\d+>', 'verb'=>'POST'),
    array('user/delete', 'pattern'=>'api/users/<id:\d+>', 'verb'=>'DELETE'),
    array('user/create', 'pattern'=>'api/users', 'verb'=>'POST'),

    '<controller:\w+>/<id:\d+>' => '<controller>/view',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
);

