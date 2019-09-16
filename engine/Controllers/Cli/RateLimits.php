<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Cli;
use Opspot\Interfaces;
use Opspot\Entities;

class RateLimits extends Cli\Controller implements Interfaces\CliControllerInterface
{

    public function __construct()
    {
        $opspot = new Core\Opspot;
        $opspot->start();
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        Core\Security\ACL::$ignore = true;
        \Opspot\Core\Events\Defaults::_();
        $scanner = new Core\Security\RateLimits\Scanner();
        $scanner->run();
    }

    public function manual()
    {
        Core\Security\ACL::$ignore = true;
        \Opspot\Core\Events\Defaults::_();

        $user = new Entities\User('sillysealion');
        $manager = new Core\Security\RateLimits\Manager();
        $manager->setInteraction('subscribe')
            ->setUser($user)
            ->impose();

        var_dump($manager->isLimited());

    }

}
