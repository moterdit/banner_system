<?php

namespace Ensi\LaravelOctaneStarter;

use Laravel\Octane\ApplicationFactory;
use Laravel\Octane\Contracts\Worker as WorkerContract;
use Laravel\Octane\Stream;
use Laravel\Octane\Swoole\Handlers\OnWorkerStart as OriginalOnWorkerStart;
use Laravel\Octane\Swoole\SwooleClient;
use Laravel\Octane\Swoole\WorkerState;
use Laravel\Octane\Worker;
use Swoole\Http\Server;
use Throwable;

class OnWorkerStart extends OriginalOnWorkerStart
{
    protected function bootWorker($server): WorkerContract
    {
        $this->workerState->client = new SwooleClient();
        try {
            return tap(new Worker(
                new ApplicationFactory($this->basePath),
                $this->workerState->client,
            ))->boot([
                'octane.cacheTable' => $this->workerState->cacheTable,
                Server::class => $server,
                WorkerState::class => $this->workerState,
            ]);
        } catch (Throwable $e) {
            Stream::throwable($e);
            return new EmergencyWorker($this->workerState->client, $e, $this->serverState);
        }
    }

}