<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Cli;
use Opspot\Interfaces;
use Opspot\Entities;

class Transcode extends Cli\Controller implements Interfaces\CliControllerInterface
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
        $transcoder = new Core\Media\Services\FFMpeg;
        $transcoder->setKey($this->getOpt('guid'));
        $transcoder->transcode();
    }
}
