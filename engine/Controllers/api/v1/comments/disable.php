<?php
/**
 * Opspot Comments API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1\comments;

use Opspot\Core;
use Opspot\Core\Data;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Helpers;
use Opspot\Core\Sockets;

class disable implements Interfaces\Api
{
    public function get($pages) {
        return Factory::response([]);
    }

    public function post($pages) {
        return Factory::response([]);
    }

    public function put($pages) {
        $response = [];

        if (is_numeric($pages[0])) {
            $activity = Core\Entities::build(new Entities\Entity($pages[0]));
            //$activity = new Entities\Activity($pages[0]);
            $activity->enableComments();

            $success = $activity->save(); // should check if it was successful?

            if (!$success) {
                $response = ['status' => 'error', 'message' => 'Error while enabling comments'];
            } else {
                $response = ['entity' => $activity->export()];
            }

        }

        return Factory::response($response);
    }

    public function delete($pages)  {
        $response = [];

        if (is_numeric($pages[0])) {
            $activity = Core\Entities::build(new Entities\Entity($pages[0]));
            $activity->disableComments();

            $success = $activity->save(); // should check if it was successful?

            if (!$success) {
                $response = ['status' => 'error', 'message' => 'Error while disabling comments'];
            } else {
                $response = ['entity' => $activity->export()];
            }
        }

        return Factory::response($response);
    }

}
