<?php
/**
 * Opspot Votes API (formerly known as thumbs)
 *
 * @author emi
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core\Di\Di;
use Opspot\Core\Security\ACL;
use Opspot\Core\Session;
use Opspot\Core\Votes\Counters;
use Opspot\Core\Votes\Manager;
use Opspot\Core\Votes\Vote;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class votes implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';
        $count = 0;

        try {
            /** @var Counters $counters */
            $counters = Di::_()->get('Votes\Counters');
            $count = $counters->get($pages[0], $direction);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'count' => $count
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return $this->put($pages);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';

        try {
            $vote = new Vote();
            $vote->setEntity($pages[0])
                ->setDirection($direction)
                ->setActor(Session::getLoggedinUser());

            /** @var Manager $manager */
            $manager = Di::_()->get('Votes\Manager');
            $manager->toggle($vote);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';

        try {
            $vote = new Vote();
            $vote->setEntity($pages[0])
                ->setDirection($direction)
                ->setActor(Session::getLoggedinUser());

            /** @var Manager $manager */
            $manager = Di::_()->get('Votes\Manager');
            $manager->cancel($vote);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([]);
    }
}
