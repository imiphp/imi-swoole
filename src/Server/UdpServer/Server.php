<?php

declare(strict_types=1);

namespace Imi\Swoole\Server\UdpServer;

use Imi\Bean\Annotation\Bean;
use Imi\Log\Log;
use Imi\Server\Protocol;
use Imi\Swoole\Server\Base;
use Imi\Swoole\Server\Contract\ISwooleUdpServer;
use Imi\Swoole\Server\Event\Param\PacketEventParam;
use Imi\Swoole\Util\Co\ChannelContainer;
use Imi\Worker;

/**
 * UDP 服务器类.
 *
 * @Bean(name="UdpServer", env="swoole")
 */
class Server extends Base implements ISwooleUdpServer
{
    /**
     * 是否支持 SSL.
     */
    private bool $ssl = false;

    /**
     * {@inheritDoc}
     */
    public function getProtocol(): string
    {
        return Protocol::UDP;
    }

    /**
     * {@inheritDoc}
     */
    protected function createServer(): void
    {
        $config = $this->getServerInitConfig();
        $this->swooleServer = new \Swoole\Server($config['host'], (int) $config['port'], (int) $config['mode'], (int) $config['sockType']);
    }

    /**
     * {@inheritDoc}
     */
    protected function createSubServer(): void
    {
        parent::createSubServer();
        $configs = &$this->config['configs'];
        foreach (static::SWOOLE_PROTOCOLS as $protocol)
        {
            $configs[$protocol] ??= false;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getServerInitConfig(): array
    {
        return [
            'host'      => $this->config['host'] ?? '0.0.0.0',
            'port'      => (int) ($this->config['port'] ?? 8080),
            'sockType'  => isset($this->config['sockType']) ? (\SWOOLE_SOCK_UDP | $this->config['sockType']) : \SWOOLE_SOCK_UDP,
            'mode'      => (int) ($this->config['mode'] ?? \SWOOLE_BASE),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function __bindEvents(): void
    {
        if ($event = ($this->config['events']['packet'] ?? true))
        {
            $this->swoolePort->on('packet', \is_callable($event) ? $event : function (\Swoole\Server $server, string $data, array $clientInfo) {
                try
                {
                    if (!Worker::isInited())
                    {
                        ChannelContainer::pop('workerInit');
                    }
                    $this->trigger('packet', [
                        'server'        => $this,
                        'data'          => $data,
                        'clientInfo'    => $clientInfo,
                    ], $this, PacketEventParam::class);
                }
                catch (\Throwable $th)
                {
                    // @phpstan-ignore-next-line
                    if (true !== $this->getBean('UdpErrorHandler')->handle($th))
                    {
                        Log::error($th);
                    }
                }
            });
        }
        else
        {
            $this->swoolePort->on('packet', static function () {
            });
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isSSL(): bool
    {
        return $this->ssl;
    }

    /**
     * {@inheritDoc}
     */
    public function isLongConnection(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function sendTo(string $ip, int $port, string $data): bool
    {
        return $this->getSwooleServer()->sendto($ip, $port, $data);
    }
}
