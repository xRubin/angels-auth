<?php

namespace angels2\auth\server;

use Swoole\Http\Server;

class HttpServer
{
    /** @var string */
    private $host;
    /** @var int */
    private $port;
    /** @var Server */
    private $http;
    /** @var RequestDispatcher */
    private $dispatcher;
    /** @var int */
    private $workerNum = 1;
    /** @var int */
    private $dispatchMode = SWOOLE_IPC_PREEMPTIVE;
    /** @var string */
    private $logFile;
    /** @var int */
    private $logLevel = 0;

    /**
     * @param string $host
     * @param int $port
     * @param RequestDispatcher $dispatcher
     */
    public function __construct(string $host, int $port, RequestDispatcher $dispatcher)
    {
        $this->host = $host;
        $this->port = $port;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getWorkerNum(): int
    {
        return $this->workerNum;
    }

    /**
     * @param int $workerNum
     * @return HttpServer
     */
    public function setWorkerNum(int $workerNum): HttpServer
    {
        $this->workerNum = $workerNum;
        return $this;
    }

    /**
     * @return int
     */
    public function getDispatchMode(): int
    {
        return $this->dispatchMode;
    }

    /**
     * @param int $dispatchMode
     * @return HttpServer
     */
    public function setDispatchMode(int $dispatchMode): HttpServer
    {
        $this->dispatchMode = $dispatchMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }

    /**
     * @param string $logFile
     * @return HttpServer
     */
    public function setLogFile(string $logFile): HttpServer
    {
        $this->logFile = $logFile;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogLevel(): int
    {
        return $this->logLevel;
    }

    /**
     * @param int $logLevel
     * @return HttpServer
     */
    public function setLogLevel(int $logLevel): HttpServer
    {
        $this->logLevel = $logLevel;
        return $this;
    }

    protected function init()
    {
        $this->http = new Server($this->getHost(), $this->getPort(), SWOOLE_BASE);
        $this->http->set([
            'worker_num' => $this->getWorkerNum(),
            'daemonize' => 1,
            'dispatch_mode' => $this->getDispatchMode(),
            'log_file' => $this->getLogFile(),
            'log_level' => $this->getLogLevel(),
        ]);

//        $config = [
//            'host' => '127.0.0.1',
//            'port' => 9100,
//            //'worker_num' => 4,
//            'worker_num' => 1,
//            'dispatch_mode' => SWOOLE_IPC_PREEMPTIVE,
//            //'reactor_num' => 4,
//            'daemonize' => 1,
//            'log_file' => __DIR__ . '/../logs/angels.auth.log',
//            'log_level' => 0, // all
//            //'group' => 'www-data',
//            //'user' => 'php',
//        ];

        //register_shutdown_function('handleFatal');
        $this->http->on('request', [$this->dispatcher, 'run']);
    }

    public function start()
    {
        $this->init();
        $this->http->start();
    }
}