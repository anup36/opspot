<?php
namespace Opspot\Core\Media;

use Opspot\Core;
use Opspot\Entities;

class AssetsFactory
{
    public static function build($entity)
    {
        $type = ucfirst($entity->subtype);

        $class = "\\Opspot\\Core\\Media\\Assets\\" . $type;

        if (!class_exists($class)) {
            throw new \Exception("Unknown asset type: {$type}");
        }

        $assets = new $class();
        $assets->setEntity($entity);

        return $assets;
    }
}
