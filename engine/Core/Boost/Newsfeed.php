<?php

namespace Opspot\Core\Boost;

use Opspot\Interfaces\BoostHandlerInterface;
use Opspot\Core;
use Opspot\Core\Data;
use Opspot\Entities;
use Opspot\Helpers;

/**
 * Newsfeed Boost handler
 */
class Newsfeed extends Network implements BoostHandlerInterface
{
    protected $handler = 'newsfeed';

    /**
     * @param mixed $entity
     * @return bool
     */
    public static function validateEntity($entity)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        return
            $entity instanceof Entities\Activity ||
            $entity instanceof Entities\Video ||
            $entity instanceof Entities\Image ||
            $entity instanceof Core\Blogs\Blog;
    }
}
