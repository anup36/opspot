<?php
/**
 * Bootstraps Opspot engine
 */

/**
 * The time with microseconds when the Opspot engine was started.
 *
 * @global float
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);
date_default_timezone_set('UTC');

define('__OPSPOT_ROOT__', dirname(__FILE__));

/**
 * Autoloader
 */
require_once(__OPSPOT_ROOT__ . '/vendor/autoload.php');

$opspot = new Opspot\Core\Opspot();
$opspot->start();
