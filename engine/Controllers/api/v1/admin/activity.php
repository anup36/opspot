<?php
/**
 * Opspot Entity Report API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Opspot\Controllers\api\v1\admin;

use Opspot\Core;
use Opspot\Core\Events\Dispatcher;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class activity implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (count($pages) < 2) {
            return Factory::response([]);
        }

        $activity = new Entities\Activity($pages[0]);
        $user = Core\Session::getLoggedInUser();
        $response = [];

        if (!$activity || !$user) {
            return Factory::response([]);
        }

        if (!$activity->canEdit($user->guid)) {
            return Factory::response(['status' => 'error']);
        }

        switch ($pages[1]) {
            case 'mature':
                $value = (bool) $_POST['value'];

                $activity->setMature($value);

                if (isset($activity->custom_data['mature'])) {
                    $activity->custom_data['mature'] = $activity->getMature();
                }

                if (isset($activity->custom_data[0]['mature'])) {
                    $activity->custom_data[0]['mature'] = $activity->getMature();
                }

                if ($activity->entity_guid) {
                    $attachment = Entities\Factory::build($activity->entity_guid);

                    if ($attachment && $attachment->guid && $attachment instanceof Interfaces\Flaggable) {
                        $attachment->setFlag('mature', $activity->getMature());
                        $attachment->save();
                    }
                }

                Dispatcher::trigger('search:index', 'all', [
                  'entity' => $activity
                ]);

                // @todo: set user flag for mature content when
                // stateless entity modifications are introduced

                $response = [ 'done' => (bool) $activity->save() ];
                break;
        }

        return Factory::response($response);
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