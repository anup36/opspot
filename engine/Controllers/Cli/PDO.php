<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core;
use Opspot\Cli;
use Opspot\Interfaces;
use Opspot\Exceptions;
use Opspot\Entities;

class PDO extends Cli\Controller implements Interfaces\CliControllerInterface
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
        $dwh = Core\Di\Di::_()->get('Database\PDO');
        $resp = $dwh->exec('SELECT * FROM suggested');
        var_dump($resp);
    }

}
