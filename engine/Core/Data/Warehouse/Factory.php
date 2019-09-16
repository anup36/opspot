<?php
/**
 * Warehouse factory
 */
namespace Opspot\Core\Data\Warehouse;

class Factory
{
    /**
     * Build a warehouse job
     */
    public function build($job)
    {
        $job = "\\Opspot\\Core\\Data\\Warehouse\\$job";
        if (class_exists($job)) {
            return new $job;
        }
    }
}
