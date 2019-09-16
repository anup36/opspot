<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core;
use Opspot\Cli;
use Opspot\Interfaces;
use Opspot\Exceptions;
use Opspot\Entities;

class GuidServer extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        $this->out('Running guid server');

        $machine = rand(0, 1000);
        $port = 5599;
        $zks = 'localhost:2181';
        
        $timer = new \Davegardnerisme\CruftFlake\Timer;
        if ($machine !== NULL) {
                $config = new \Davegardnerisme\CruftFlake\FixedConfig($machine);
        } else {
                $config = new \Davegardnerisme\CruftFlake\ZkConfig($zks);
        }
        $generator = new \Davegardnerisme\CruftFlake\Generator($config, $timer);
        $zmqRunner = new \Davegardnerisme\CruftFlake\ZeroMq($generator, $port);
        $zmqRunner->run();
    }

}
