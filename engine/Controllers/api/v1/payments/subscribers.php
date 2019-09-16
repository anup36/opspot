<?php
/**
 * Opspot Payments API - Subscribers
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1\payments;

use Opspot\Core;
use Opspot\Helpers;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Payments;

class subscribers implements Interfaces\Api
{
    /**
     * Returns an entities subscribers
     * @param array $pages
     *
     * API:: /v1/payments/subscribers/:plan
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
        $repo = new Payments\Plans\Repository();

        $guids = $repo
            ->setEntityGuid(Core\Session::getLoggedInUser()->guid)
            ->getAllSubscribers($pages[1], [
                'offset' => $offset
            ]);

        if ($offset) {
            array_shift($guids);
        }

        $response = [];

        if ($guids) {
            $subscribers = Core\Entities::get([ 'guids' => $guids ]);

            $response['subscribers'] = Factory::exportable($subscribers);

            if ($subscribers) {
                $response['load-next'] = (string) end($subscribers)->guid;
            }
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
