<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\Event\Param;

use Imi\Event\EventParam;
use Imi\Swoole\Server\Contract\ISwooleServer;

class CloseEventParam extends EventParam
{
    /**
     * 服务器对象
     */
    public ?ISwooleServer $server = null;

    /**
     * 客户端连接的标识符.
     */
    public int|string $clientId = 0;

    /**
     * 来自那个reactor线程.
     */
    public int $reactorId = 0;
}
