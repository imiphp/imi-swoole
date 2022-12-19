<?php

declare(strict_types=1);

use function Imi\env;

return [
    // 项目根命名空间
    'namespace'         => 'Imi\Swoole\Test\RedisSessionServer',

    // 配置文件
    'configs'           => [
        'beans'        => __DIR__ . '/beans.php',
    ],

    // 扫描目录
    'beanScan'          => [
        'Imi\Swoole\Test\RedisSessionServer\Listener',
    ],

    // 组件命名空间
    'components'        => [
        'Swoole' => 'Imi\Swoole',
        'Macro'  => 'Imi\Macro',
    ],

    // 日志配置
    'logger'            => [
        'channels' => [
            'imi' => [
                'handlers' => [
                    [
                        'class'     => \Imi\Log\Handler\ConsoleHandler::class,
                        'formatter' => [
                            'class'     => \Imi\Log\Formatter\ConsoleLineFormatter::class,
                            'construct' => [
                                'format'                     => null,
                                'dateFormat'                 => 'Y-m-d H:i:s',
                                'allowInlineLineBreaks'      => true,
                                'ignoreEmptyContextAndExtra' => true,
                            ],
                        ],
                    ],
                    [
                        'class'     => \Monolog\Handler\RotatingFileHandler::class,
                        'construct' => [
                            'filename' => \dirname(__DIR__) . '/logs/log.log',
                        ],
                        'formatter' => [
                            'class'     => \Monolog\Formatter\LineFormatter::class,
                            'construct' => [
                                'dateFormat'                 => 'Y-m-d H:i:s',
                                'allowInlineLineBreaks'      => true,
                                'ignoreEmptyContextAndExtra' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // 主服务器配置
    'mainServer'        => [
        'namespace'    => 'Imi\Swoole\Test\RedisSessionServer\ApiServer',
        'type'         => Imi\Swoole\Server\Type::HTTP,
        'host'         => env('SERVER_HOST', '127.0.0.1'),
        'port'         => 13001,
        'configs'      => [
            'worker_num'    => 1,
        ],
    ],

    // 子服务器（端口监听）配置
    'subServers'        => [
    ],

    // 连接池配置
    'pools'             => [
        // 主数据库
        'maindb'          => [
            'pool'        => [
                'class'        => \Imi\Swoole\Db\Pool\CoroutineDbPool::class,
                'config'       => [
                    'maxResources'    => 10,
                    'minResources'    => 0,
                ],
            ],
            'resource'    => [
                'host'        => env('MYSQL_SERVER_HOST', '127.0.0.1'),
                'port'        => env('MYSQL_SERVER_PORT', 3306),
                'username'    => env('MYSQL_SERVER_USERNAME', 'root'),
                'password'    => env('MYSQL_SERVER_PASSWORD', 'root'),
                'database'    => 'mysql',
                'charset'     => 'utf8mb4',
            ],
        ],
        'redis'           => [
            'pool'        => [
                'class'        => \Imi\Swoole\Redis\Pool\CoroutineRedisPool::class,
                'config'       => [
                    'maxResources'    => 10,
                    'minResources'    => 0,
                ],
            ],
            'resource'    => [
                'host'      => env('REDIS_SERVER_HOST', '127.0.0.1'),
                'port'      => env('REDIS_SERVER_PORT', 6379),
                'password'  => env('REDIS_SERVER_PASSWORD'),
            ],
        ],
        'redisSession'    => [
            'pool'        => [
                'class'        => \Imi\Swoole\Redis\Pool\CoroutineRedisPool::class,
                'config'       => [
                    'maxResources'    => 10,
                    'minResources'    => 1,
                ],
            ],
            'resource'    => [
                'host'      => env('REDIS_SERVER_HOST', '127.0.0.1'),
                'port'      => env('REDIS_SERVER_PORT', 6379),
                'password'  => env('REDIS_SERVER_PASSWORD'),
                'serialize' => false,
            ],
        ],
    ],

    // 数据库配置
    'db'                => [
        // 默认连接池名
        'defaultPool'    => 'maindb',
    ],

    // redis 配置
    'redis'             => [
        // 默认连接池名
        'defaultPool'   => 'redis',
    ],
];
