<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core\Di\Di;
use Opspot\Interfaces;
use Opspot\Cli;
use Opspot\Core\Data\Cassandra\Prepared;
use Opspot\Entities;
use Opspot\Core;
use Opspot\Core\Analytics\Iterators\SignupsOffsetIterator;

class CreateFoundersIndex extends Cli\Controller implements Interfaces\CliControllerInterface
{

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);        
        $db = Di::_()->get('Database\Cassandra\Cql');
        $entities_by_time = new Core\Data\Call('entities_by_time');

        $users = new SignupsOffsetIterator;
        $users->setOffset($this->getOpt('offset') ?: '');

        $i = 0;
        foreach ($users as $user) {
            $i++;
            echo "\n[$i]:$user->guid";
            if ($user->founder) {
                echo "\n[$i]:$user->guid indexed";
                $entities_by_time->insert('user:founders',  [ (string) $user->guid => (string) $user->guid ]);
            }
        }

    }
}
