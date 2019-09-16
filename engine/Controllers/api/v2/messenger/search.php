<?php
/**
 * Opspot Messenger Search
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v2\messenger;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Entities;
use Opspot\Helpers;
use Opspot\Core\Messenger;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class search implements Interfaces\Api
{

    /**
     * Returns users and conversation guids
     * @param array $pages
     *
     * API:: /v1/conversations/search
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        $response = [];

        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
              'status' => 'error',
              'message' => 'Missing query'
            ]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 24;

        /** @var Core\Search\Search $search */
        $search = Di::_()->get('Search\Search');

        $entities = $search->suggest('user', $_GET['q'], $limit);
        $response = [];

        if ($entities) {
            $guids = [];
            foreach ($entities as $entity) {
                if ($entity['guid']) {
                    $guids[] = $entity['guid'];
                }
            }

            $users = Core\Entities::get([
              'guids' => $guids
            ]);
            
            $conversations = [];
            foreach ($users as $user) {
                if ($user->guid == Core\Session::getLoggedInUserGuid()) {
                    continue;
                }

                $conversations[] = (new Entities\Conversation())
                                      ->setParticipant($user->guid)
                                      ->setParticipant(Core\Session::getLoggedInUserGuid());
            }

            $response['conversations'] = Factory::exportable($conversations);
            $response['load-next'] = (int) $_GET['offset'] + $_GET['limit'] + 1;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
