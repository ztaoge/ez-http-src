<?php
namespace EzHttp;

Class Worker
{
    public $mainSocket;
    // child worker num
    public $count = 1;

    public $host = '0.0.0.0:80';

    /**
     * @var callable
     */
    public $onMessage;

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
                    // accept
                    $newSocket = @stream_socket_accept($socket, -1, $remote_address);
                    $buffer = @fread($newSocket, 65536);
                    //TODO: handle response
                    $msg = "hello world!!!\n";
                    $str = Http::httpEncode($msg);
                    @fwrite($newSocket, $str, strlen($str));
                    @fclose($newSocket);
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

    public function listenAndAccept($host)
    {
        $socket = stream_socket_server("tcp://$host", $errno, $errstr);
        $newSocket = @stream_socket_accept($socket, -1, $remote_address);
        $buffer = @fread($newSocket, 65536);

        return $newSocket;
    }

    public function send()
    {}
}