<?php
/**
 * Opspot Data Client Factory
 */
namespace Opspot\Core\data;

class Client
{
    private static $default = '\Opspot\Core\Data\cassandra\client';
    private static $clients = [];

    /**
     * Build the client
     * @param string $table - Table controller
     * @param string $handler - The handler to load
     * @return object
     */
    public static function build($handler = null, $options = array())
    {
        if (!$handler) {
            $handler = self::$default;
        }
        
        
        if (substr($handler, 0, 1) != "\\") {
            $handler = "\\Opspot\\Core\\Data\\$handler\\Client";
        }

        if (isset(self::$clients[$handler])) {
            return self::$clients[$handler];
        }

        if (class_exists($handler)) {
            return self::$clients[$handler] = new $handler($options);
        } else {
            throw new \Exception("Factory not found");
        }
    }
}
