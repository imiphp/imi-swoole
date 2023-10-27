<?php

declare(strict_types=1);

namespace Imi\Swoole\Util\Co;

use Imi\Facade\BaseFacade;

/**
 * @method static bool                      push(string $id, $data, float $timeout = -1.0)
 * @method static mixed                     pop(string $id, float $timeout = -1.0)
 * @method static mixed                     finallyPop(string $id, float $timeout = -1.0)
 * @method static array                     stats(string $id)
 * @method static bool                      close(string $id)
 * @method static int                       length(string $id)
 * @method static bool                      isEmpty(string $id)
 * @method static bool                      isFull(string $id)
 * @method static \Swoole\Coroutine\Channel getChannel(string $id)
 * @method static bool                      hasChannel(string $id)
 * @method static void                      removeChannel(string $id)
 */
#[
    \Imi\Facade\Annotation\Facade(class: \Yurun\Swoole\CoPool\ChannelContainer::class)
]
class ChannelContainer extends BaseFacade
{
}
