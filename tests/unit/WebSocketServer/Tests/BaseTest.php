<?php

declare(strict_types=1);

namespace Imi\Swoole\Test\WebSocketServer\Tests;

abstract class BaseTest extends \Imi\Swoole\Test\BaseTest
{
    /**
     * 请求主机.
     *
     * @var string
     */
    protected $host = 'ws://127.0.0.1:13002/';
}
