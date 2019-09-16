<?php
/**
 * Opspot Group API
 * Groups listing endpoints
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Entities;
use Opspot\Core\Session;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Groups\Membership;

class groups implements Interfaces\Api
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/groups/:filter
     */
    public function get($pages)
    {
        $groups = [];
        $user = Session::getLoggedInUser();

        $indexDb = Di::_()->get('Database\Cassandra\Indexes');
        $relDb = Di::_()->get('Database\Cassandra\Relationships');

        if (!isset($pages[0])) {
            $pages[0] = "featured";
        }

        $opts = [
          'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
          'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
        ];

        switch ($pages[0]) {
          case "top":
          case "featured":
            $guids = $indexDb->get('group:featured', $opts);
            end($guids); //get last in array
            $response['load-next'] =  (string) key($guids);
            break;
          case "member":
            $manager = new Membership();
            $guids = $manager->getGroupsByMember([
                'user_guid' => $user->guid,
                'offset' => (int) $_GET['offset'],
            ]);
            $response['load-next'] = count($guids) + (int) $_GET['offset'];
            break;
          case "all":
          default:
            $guids = $indexDb->get('group', $opts);
            break;
        }

        if (!$guids) {
            return Factory::response([]);
        }

        $groups = Entities::get(['guids' => $guids]);

        $response['groups'] = Factory::exportable($groups);
        $response['entities'] = Factory::exportable($groups);

        if (!isset($response['load-next']) && $groups) {
            $response['load-next'] = (string) end($groups)->getGuid();
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
