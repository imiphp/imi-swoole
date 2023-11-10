<?php

declare(strict_types=1);

namespace Imi\Swoole\Listener;

use Imi\App;
use Imi\Bean\Annotation\Listener;
use Imi\Cli\ImiCommand;
use Imi\Event\Event;
use Imi\RequestContext;
use Imi\Server\Http\Listener\HttpRouteInit;
use Imi\Server\Server;
use Imi\Server\ServerManager;
use Imi\Swoole\Server\Event\Listener\IWorkerStartEventListener;
use Imi\Swoole\Server\Event\Param\WorkerStartEventParam;
use Imi\Swoole\SwooleWorker;
use Imi\Util\Imi;
use Imi\Worker;

#[Listener(eventName: 'IMI.MAIN_SERVER.WORKER.START', priority: \Imi\Util\ImiPriority::IMI_MIN, one: true)]
class AfterWorkerStart implements IWorkerStartEventListener
{
    /**
     * {@inheritDoc}
     */
    public function handle(WorkerStartEventParam $e): void
    {
        // 项目初始化事件
        if (0 === Worker::getWorkerId() && !$this->checkInitFlagFile($initFlagFile = Imi::getRuntimePath(str_replace('\\', '-', App::getNamespace()) . '.app.init')))
        {
            Event::trigger('IMI.APP.INIT', [], $e->getTarget());

            file_put_contents($initFlagFile, SwooleWorker::getMasterPid());

            ImiCommand::getOutput()->writeln('<info>App Inited</info>');
        }
        $originServer = RequestContext::get('server');
        foreach (ServerManager::getServers() as $name => $server)
        {
            RequestContext::set('server', $server);
            Server::getInstance($name);
        }
        RequestContext::set('server', $originServer);
        $httpRouteInit = new HttpRouteInit();
        $httpRouteInit->handle($e);
    }

    /**
     * 检测是否当前服务已初始化.
     */
    private function checkInitFlagFile(string $initFlagFile): bool
    {
        return is_file($initFlagFile) && filemtime($initFlagFile) >= $_SERVER['REQUEST_TIME'] && file_get_contents($initFlagFile) == SwooleWorker::getMasterPid();
    }
}
