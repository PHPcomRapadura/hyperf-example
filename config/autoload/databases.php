<?php

declare(strict_types=1);

use function Hyperf\Support\env;

$connections = [
    'postgres' => [
        'driver' => 'pgsql',
        'read' => [
            'host' => [
                env('DB_HOST_READER', env('DB_HOST')),
            ],
            'username' => env('DB_USERNAME_READER', env('DB_USERNAME', 'root')),
            'password' => env('DB_PASSWORD_READER', env('DB_PASSWORD', '')),
        ],
        'write' => [
            'host' => [
                env('DB_HOST'),
            ],
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ],
        'port' => env('DB_PORT', 5432),
        'database' => env('DB_DATABASE', 'hyperf-example'),
        'charset' => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix' => env('DB_PREFIX', ''),
        'schema' => env('POSTGRES_SCHEMA', 'public'),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 30,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('DB_MAX_IDLE_TIME', 60),
        ],
        'options' => [
            PDO::ATTR_TIMEOUT => 5,
        ],
    ],
    'mysql' => [
        'driver' => 'mysql',
        'read' => [
            'host' => [
                env('MY_SQL_DB_HOST_READER', 'mysql'),
            ],
            'username' => env('MY_SQL_DB_USERNAME_READER', 'root'),
            'password' => env('MY_SQL_DB_PASSWORD_READER', 'root'),
            'port' => env('MY_SQL_DB_PORT_READER', 3306),
        ],
        'write' => [
            'host' => [
                env('MY_SQL_DB_HOST_WRITE', 'mysql'),
            ],
            'username' => env('MY_SQL_DB_USERNAME_WRITE', 'root'),
            'password' => env('MY_SQL_DB_PASSWORD_WRITE', 'root'),
            'port' => env('MY_SQL_DB_PORT_WRITE', 3306),
        ],
        'database' => env('DB_DATABASE', 'hyperf-example'),
        'port' => env('MY_SQL_DB_PORT_READER', 3306),
        'charset' => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix' => env('DB_PREFIX', ''),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 30,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('DB_MAX_IDLE_TIME', 60),
        ],
        'options' => [
            PDO::ATTR_TIMEOUT => 5,
        ],
    ],
];

$connection = env('DB_CONNECTION', 'postgres');
return [
    'default' => $connection[$connection],
    'connections' => $connections,
    'connection' => $connection,
];
