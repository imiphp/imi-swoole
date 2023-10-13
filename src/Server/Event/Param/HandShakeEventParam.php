<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\Event\Param;

use Imi\Event\EventParam;
use Imi\Server\Http\Message\Contract\IHttpRequest;
use Imi\Server\Http\Message\Contract\IHttpResponse;

class HandShakeEventParam extends EventParam
{
    /**
     * swoole 请求对象
     */
    public ?IHttpRequest $request = null;

    /**
     * swoole 响应对象
     */
    public ?IHttpResponse $response = null;
}
