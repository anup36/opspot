<?php
/**
 * A simple factory
 */

namespace Opspot\Core\Media\Services;

class Factory
{

    /**
     * Build the service factory
     * @param string $service
     * @return ServiceInterface
     * @throws Exception - "Service not found"
     */
    public static function build($service)
    {
        $service = ucfirst($service);
        $class = "Opspot\\Core\\Media\\Services\\$service";
        if (class_exists($class)) {
            return new $class;
        }

        throw new \Exception("Service `$service` not found");
    }

}
