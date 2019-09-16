<?php
namespace Opspot\Core\Boost;

use Opspot\Interfaces\BoostHandlerInterface;
use Opspot\Core;
use Opspot\Core\Data;
use Opspot\Entities;
use Opspot\Helpers;

/**
 * Content Boost handler
 */
class Content extends Network implements BoostHandlerInterface
{
    protected $handler = 'content';

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
            $entity instanceof Entities\User ||
            $entity instanceof Entities\Video ||
            $entity instanceof Entities\Image ||
            $entity instanceof Core\Blogs\Blog ||
            $entity instanceof Entities\Group;
    }
}
