<?php

declare(strict_types=1);

namespace Imi\Swoole\Cron\Listener;

use Imi\Aop\Annotation\Inject;
use Imi\App;
use Imi\Bean\Annotation\Listener;
use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Util\Process\ProcessAppContexts;
use Imi\Util\Process\ProcessType;

#[Listener(eventName: 'IMI.PIPE_MESSAGE.cronTask')]
class WorkerPartPipeMessage implements IEventListener
{
    /**
     * @var \Imi\Cron\CronManager
     */
    #[Inject(name: 'CronManager')]
    protected $cronManager;

    /**
     * @var \Imi\Cron\CronWorker
     */
    #[Inject(name: 'CronWorker')]
    protected $cronWorker;

    /**
     * {@inheritDoc}
     */
    public function handle(EventParam $e): void
    {
        if (ProcessType::WORKER !== App::get(ProcessAppContexts::PROCESS_TYPE))
        {
            return;
        }
        $data = $e->getData()['data'];
        $this->cronWorker->exec($data['id'], $data['data'], $data['task'], $data['type']);
    }
}
