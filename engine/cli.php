<?php
$_SCRIPTNAME = basename(__FILE__);

if (PHP_SAPI !== 'cli') {
    echo "{$_SCRIPTNAME} this is a CLI script" . PHP_EOL;
    exit(1);
}

require_once(dirname(__FILE__) . "/vendor/autoload.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

array_shift($argv);

if (isset($argv[0]) && $argv[0] == 'help') {
    $help = true;
    array_shift($argv);
} elseif (array_search('--help', $argv)) {
    $help = true;
}

if (!$argv) {
    // TODO: list handlers?
    echo "{$_SCRIPTNAME}: specify a controller" . PHP_EOL;
    exit(1);
}

try {
    $opspot = new Opspot\Core\Opspot();
    $opspot->loadConfigs();
    $opspot->loadLegacy();
    //loading events will instantiate all of the dependencies which won't be configured yet if we're installing
    if ($argv[0] !== 'install') {
        $opspot->loadEvents();
    }

    $handler = Opspot\Cli\Factory::build($argv);

    if (!$handler) {
        echo "{$_SCRIPTNAME}: controller `{$argv[0]}` not found" . PHP_EOL;
        exit(1);
    } elseif (!($handler instanceof Opspot\Interfaces\CliControllerInterface)) {
        echo "{$_SCRIPTNAME}: `{$argv[0]}` is not a controller" . PHP_EOL;
        exit(1);
    }

    if (method_exists($handler, 'setApp')) {
        $handler->setApp($opspot);
    }

    if (isset($help)) {
        $handler->help($handler->getExecCommand());
    } else {
        $errorlevel = $handler->{$handler->getExecCommand()}();
        echo PHP_EOL;
        exit((int) $errorlevel);
    }
} catch (Opspot\Exceptions\CliException $e) {
    echo PHP_EOL . "{$_SCRIPTNAME}: [ERROR] {$e->getMessage()}" . PHP_EOL;
    exit(1);
} catch (\Exception $e) {
    $exceptionClass = get_class($e);
    echo PHP_EOL . "{$_SCRIPTNAME}: [EXCEPTION:{$exceptionClass}] {$e->getMessage()}" . PHP_EOL;
    exit(1);
}

echo PHP_EOL;
exit(0);
