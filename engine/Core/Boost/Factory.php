<?php
namespace Opspot\Core\Boost;

use Opspot\Core\Data;
use Opspot\Interfaces;

/**
 * A factory providing handlers boosting items
 */
class Factory
{
    public static function getClassHandler($handler)
    {
        $handler = ucfirst($handler);
        $handler = "Opspot\\Core\\Boost\\$handler";
        if (class_exists($handler)) {
            return $handler;
        }
        throw new \Exception("Handler not found");
    }

    /**
     * Build the handler
     * @param  string $handler
     * @param  array  $options (optional)
     * @return BoostHandlerInterface
     */
    public static function build($handler, $options = array(), $db = null)
    {
        if($handler == 'newsfeed')
            $handler = 'network';
        $handler = ucfirst($handler);
        $handler = "Opspot\\Core\\Boost\\$handler";
        if (class_exists($handler)) {
            $class = new $handler($options, $db);
            if ($class instanceof Interfaces\BoostHandlerInterface) {
                return $class;
            }
        }
        throw new \Exception("Handler not found");
    }
}