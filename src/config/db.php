<?php

/**
 * @var $mysqlDatabase
 * @var $mysqlUserName
 * @var $mysqlPassword
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mysql;dbname=' . $mysqlDatabase,
    'username' => $mysqlUserName,
    'password' => $mysqlPassword,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
