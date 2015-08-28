<?php
define("DB_NAME","nemr_final");
define("DB_USER","read_user");
define("DB_PASSWORD","read_user");
define("DB_SERVER","10.0.3.117");

define("ASS_DB_NAME","nemr_testing");
define("ASS_DB_USER","nemr");
define("ASS_DB_PASSWORD","nemr");
define("ASS_DB_SERVER","10.0.3.99");

//define("DB_NAME","nemr");
//define("DB_USER","schoolerp");
//define("DB_PASSWORD","schoolerp");
//define("DB_SERVER","10.1.9.104");

//define("DB_NAME","nemr");
//define("DB_USER","schoolerp");
//define("DB_PASSWORD","schoolerp");
//define("DB_SERVER","10.1.9.57");

//define("DB_NAME","nemr_07april");
//define("DB_USER","vijay");
//define("DB_PASSWORD","vijay");
//define("DB_SERVER","10.1.9.115");

//define("DB_NAME","nemr_07june");
//define("DB_USER","nemr");
//define("DB_PASSWORD","nemr");
//define("DB_SERVER","10.1.9.134");

//define("DB_NAME","nemr_testing");
//define("DB_USER","nemr");
//define("DB_PASSWORD","nemr");
//define("DB_SERVER","10.0.3.99");


return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => DB_SERVER,
                    'port' => '3306',
                    'user' => DB_USER,
                    'password' => DB_PASSWORD,
                    'dbname' => DB_NAME,
                    'charset' => 'utf8'
                )),
            'orm_assessment' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => ASS_DB_SERVER,
                    'port' => '3306',
                    'user' => ASS_DB_USER,
                    'password' => ASS_DB_PASSWORD,
                    'dbname' => ASS_DB_NAME,
                    'charset' => 'utf8'
                )),)),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'adapters' => array(
            'assessment_db' => array(
                'driver' => 'Pdo',
                'dsn' => "mysql:dbname=" . ASS_DB_NAME . ";host=" . ASS_DB_SERVER,
                'user' => ASS_DB_USER,
                'password' => ASS_DB_PASSWORD,
            ),
        ),
    ),
    'slave' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'slave2' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'slave3' => array(
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'enabled' => true,
            'strict' => true,
            'flush_early' => false,
            'cache_dir' => 'data/cache',
            'matcher' => array(),
            'collectors' => array()
        ),
        'events' => array(
            'enabled' => true,
            'collectors' => array(),
            'identifiers' => array()
        ),
        'toolbar' => array(
            'enabled' => true,
            'auto_hide' => false,
            'position' => 'bottom',
            'version_check' => false,
            'entries' => array()
        )
    ),
);
