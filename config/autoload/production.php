<?php
//define("DB_NAME","nemr_testing");
//define("DB_USER","nemr");
//define("DB_PASSWORD","nemr");
//define("DB_SERVER","10.0.3.99");

define("DB_NAME","nemr_final");
define("DB_USER","read_user");
define("DB_PASSWORD","read_user");
define("DB_SERVER","10.0.3.117");
return array(
   'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => DB_SERVER,
                    'port' => '3306',
                    'user' => DB_USER,
                    'password' =>DB_PASSWORD,
                    'dbname' => DB_NAME,
                    'charset'=>'utf8'
                )))),
    'db' => array(
        'driver' => 'Pdo',
          'dsn' => "mysql:dbname=".DB_NAME.";host=".DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
    ),
    'slave' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=".DB_NAME.";host=".DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
    ),
    'slave2' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=".DB_NAME.";host=".DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
    ),
    'slave3' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=".DB_NAME.";host=".DB_SERVER,
        'user' => DB_USER, 
    	'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
    ),
   'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
    )
 );