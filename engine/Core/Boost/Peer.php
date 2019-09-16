<?php
namespace Opspot\Core\Boost;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Data;
use Opspot\Interfaces;
use Opspot\Entities;
use Opspot\Helpers;
use Opspot\Core\Payments;

/**
 * Peer Boost Handler
 */
class Peer implements Interfaces\BoostHandlerInterface
{
    private $guid;

    public function __construct($options)
    {
        if (isset($options['destination'])) {
            $this->guid = $options['destination'];
        }
    }

    /**
     * Boost an entity. Not used.
     * @param int|object $entity
     * @param  int $points
     * @return null
     */
    public function boost($entity, $points)
    {
        return null;
    }

    /**
     * Gets a single boost entity
     * @param  mixed  $guid
     * @return object
     */
    public function getBoostEntity($guid)
    {
        /** @var Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        return $repository->getEntity('peer', $guid);
    }

    /**
     * Return a boost. Not used.
     * @deprecated
     * @return array
     */
    public function getBoost($offset = "")
    {

       ///
       //// THIS DOES NOT APPLY BECAUSE IT'S PRE-AGREED
       ///
    }


    public function accept($entity, $impressions)
    {
        return false;
    }

    /**
     * @param mixed $entity
     * @return boolean
     */
    public static function validateEntity($entity)
    {
        return true;
    }
}
