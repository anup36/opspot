<?php
namespace Opspot\Controllers\Cli;

use Opspot\Core\Opspot;
use Opspot\Cli;
use Opspot\Core\Feeds\Top\Manager;
use Opspot\Exceptions\CliException;
use Opspot\Interfaces;

class Top extends Cli\Controller implements Interfaces\CliControllerInterface
{
    /** @var Manager */
    private $manager;

    public function __construct()
    {
        $opspot = new Opspot();
        $opspot->start();
        $this->manager = new Manager();
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli top sync_<type>');
    }

    public function exec()
    {
        $this->out('Syntax usage: cli top sync_<type>');
    }

    public function sync_activity()
    {
        $period = $this->getOpt('period') ?? null;

        if (!$period) {
            throw new CliException('Missing --period flag');
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $this->out("Syncing 'activity'... {$period}");

        $this->manager->run([
            'type' => 'activity',
            'period' => $period
        ]);

        $this->out("\nCompleted syncing 'activity'.");
    }
}
