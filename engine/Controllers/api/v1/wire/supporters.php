<?php
/**
 * Opspot Wire Supporters
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Opspot\Controllers\api\v1\wire;

use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Wire;
use Opspot\Entities;
use Opspot\Entities\User;

class supporters implements Interfaces\Api
{
    /**
     * GET
     */
    public function get($pages)
    {
        $response = [];
        $actor_guid = isset($pages[0]) ? $pages[0] : Core\Session::getLoggedInUser()->guid;

        $repo = Di::_()->get('Wire\Repository');

        $type = isset($_GET['type']) ? $_GET['type'] : 'received';
        $start = isset($_GET['start']) ? ((int) $_GET['start']) : (new \DateTime('midnight'))->modify("-30 days")->getTimestamp();
        $method = isset($_GET['method']) ? $_GET['method'] : 'money';

        $timeframe = [
          'gte' => $start,
          'lte' => time()
        ];

        switch ($type) {
            case 'sent':
                $result = $repo->getList([
                    'sender_guid' => $actor_guid,
                    'timestamp' => $timeframe,
                    'limit' => 1000,
                    'offset' => base64_decode($_GET['offset']),
                ]);
                break;

            case 'received':
                $result = $repo->getList([
                    'receiver_guid' => $actor_guid,
                    'timestamp' => $timeframe,
                    'limit' => 1000,
                    'offset' => base64_decode($_GET['offset']),
                ]);
                break;

            default:
            return Factory::response([
                'status' => 'error',
                'message' => 'Unknown type'
            ]);
        }

        $response['wires'] = Factory::exportable($result['wires']);
        $response['load-next'] = $result['token'];

        return Factory::response($response);
    }

    /**
     * POST
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
