<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\TcpServer\Listener;

use Imi\Bean\Annotation\ClassEventListener;
use Imi\Server\ConnectionContext\Traits\TConnectionContextRelease;
use Imi\Swoole\Server\Event\Listener\ICloseEventListener;
use Imi\Swoole\Server\Event\Param\CloseEventParam;

/**
 * Close事件后置处理.
 */
#[ClassEventListener(className: \Imi\Swoole\Server\TcpServer\Server::class, eventName: 'close', priority: \Imi\Util\ImiPriority::IMI_MIN)]
class AfterClose implements ICloseEventListener
{
    use TConnectionContextRelease;

    /**
     * {@inheritDoc}
     */
    public function handle(CloseEventParam $e): void
    {
        $this->release($e->clientId);
    }
}
