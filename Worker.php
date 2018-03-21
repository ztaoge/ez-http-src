<?php
namespace EzHttp;

Class Worker
{
    public $mainSocket;
    // child worker num
    public $count = 1;

    /**
     * @var null|string
     */
    public $host = '0.0.0.0:80';

    /**
     * @var string application root dir
     */
    public $appRoot = ROOT . '/Application';

    public function __construct($host = null)
    {
        if ($host) {
            $this->host = $host;
        }
    }

    public function run()
    {
        // fork worker
        $this->forkWorker();
        // monitor worker
        $this->monitorWorker();
    }

    /**
     * TODO: 频繁的实例化，销毁对象，性能是否有问题？可能会出现OOM问题？能否引入对象池？
     * fork worker
     * @throws \Exception
     */
    public function forkWorker()
    {
        $socket = stream_socket_server("tcp://$this->host", $errno, $errstr);
        for ($i = 0; $i < $this->count; $i++) {
            $pid = pcntl_fork();
            if ($pid > 0) {
                //parent worker
            } elseif ($pid == 0) {
                //child worker
                while (1) {
                    $http = new Http();
                    // accept
                    $newSocket = @stream_socket_accept($socket, -1, $remote_address);
                    $buffer = @fread($newSocket, 65536);
                    $http->httpDecode($buffer);
                    $http->handle();
//                    var_dump($http);
//                    $msg = "hello world!!!\n";
                    $str = $http->response;
                    @fwrite($newSocket, $str, strlen($str));
                    @fclose($newSocket);
                    unset($http);
                }
            } else {
                throw new \Exception('fork worker fail');
            }
        }
    }

    public function monitorWorker()
    {
        // 回收结束的子进程
        while (1) {
            pcntl_wait($status, WUNTRACED);
        }
    }

    public function send()
    {}
}