<?php
namespace EzHttp;

Class Worker
{
    // child worker num
    public $count = 1;

    public function __construct()
    {
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
        for ($i = 0; $i < $this->count; $i++) {
            $pid = pcntl_fork();
            if ($pid > 0) {
                //parent worker
            } elseif ($pid == 0) {
                //child worker
                while (1) {
                    //TODO: accept connection
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
}