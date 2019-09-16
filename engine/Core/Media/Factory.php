<?php
namespace Opspot\Core\Media;

use Opspot\Core;
use Opspot\Entities;

class Factory
{
    private static $allowed = [
        'Image',
        'Video'
    ];

    public static function build($clientType)
    {
        $type = ucfirst($clientType);

        if (!in_array($type, static::$allowed)) {
            throw new \Exception("Unknown entity type: {$type}");
        }

        $class = "\\Opspot\\Entities\\" . $type;
        return new $class();
    }
}
