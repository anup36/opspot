<?php
/**
 * Opspot Notification API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Notification;
use Opspot\Core\Notification\Settings;
use Opspot\Interfaces;
use Opspot\Helpers;
use Opspot\Api\Factory;
use Opspot\Entities;
use Opspot\Entities\Notification as NotificationEntity;

/**
 *
 * Notifications API
 *
 * Endpoint: /v1/notifications/
 *
 */
// @codingStandardsIgnoreStart
class notifications implements Interfaces\Api
{
    // @codingStandardsIgnoreEnd

    use \Opspot\Traits\HttpMethodsInput;
    use \Opspot\Traits\CurrentUser;

    const MAX_NOTIFICATIONS_PER_PAGE = 50;

    /**
     * GET method handler
     * @param  array $pages
     * @return string
     */
    public function get($pages)
    {
        Factory::isLoggedIn();
        $response = [];

        if (!isset($pages[0])) {
            $pages = ['list'];
        }

        $repository = Di::_()->get('Notification\Manager');
        $repository->setUser(Core\Session::getLoggedInUser());

        $counters = new Notification\Counters();

        switch ($pages[0]) {

            case 'count':
                $response['count'] = $counters->getCount();
                break;
            case 'settings':
                Factory::isLoggedIn();
                $toggles = (new Settings\PushSettings())
                    ->setUserGuid(Core\Session::getLoggedinUser()->guid)
                    ->getToggles();
                $response['toggles'] = $toggles;
                break;
            case 'single':
                /** @var Notification\Manager $manager */
                $manager = Di::_()->get('Notification\Manager');
                $manager->setUser(Core\Session::getLoggedinUser());

                $notification = $manager->getSingle($pages[1]);

                if (!$notification) {
                    return Factory::response([]);
                }

                $response['notification'] = $this->polyfillResponse([$notification])[0];
                break;
            case 'list':
            default:
                Factory::isLoggedIn();

                if (!$offset) {
                    $counters->resetCounter();
                }

                $limit = (int) static::getQueryValue('limit') ?: 12;
                $offset = (string) static::getQueryValue('offset') ?: '';
                $filter = $pages[0];

                if ($filter === 'list' || $filter === 'all') {
                    $filter = '';
                }

                if ($limit > static::MAX_NOTIFICATIONS_PER_PAGE) {
                    $limit = static::MAX_NOTIFICATIONS_PER_PAGE;
                }

                $manager = Di::_()->get('Notification\Manager');
                $manager->setUser(Core\Session::getLoggedinUser());
                $notifications = $manager->getList([
                    'type' => $filter,
                    'limit' => $limit,
                    'offset' => $offset
                ]);

                if (!$notifications) {
                    return Factory::response([]);
                }

                $response['notifications'] = $this->polyfillResponse($notifications);
                $response['load-next'] = (string) $notifications->getPagingToken();
                //$response['load-previous'] = (string) key($notifications)->getGuid();

                break;

        }

        return Factory::response($response);
    }

    /**
     * POST method handler
     * @param  array $pages
     * @return string
     */
    public function post($pages)
    {
        if (!isset($pages[0])) {
            $pages[0] = 'token';
        }
        switch ($pages[0]) {
            case "settings":
                $settings = new Settings\PushSettings();
                $settings->setToggle($_POST['id'], $_POST['toggle'])
                    ->setUserGuid(Core\Session::getLoggedinUser()->guid)
                    ->save();
                break;
            case "token":
                $service = static::getPostValue('service', [ 'required' => true ]);
                $passed_token = static::getPostValue('token', [ 'required' => true ]);

                $token = \Surge\Token::create([
                    'service' => $service,
                    'token' => $passed_token
                ]);

                (new Core\Data\Call('entities'))
                    ->insert(static::getCurrentUserGuid(), [ 'surge_token' => $token ]);
            break;
        }

        return Factory::response([]);
    }

    /**
     * Not used
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * Not used
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }

    /**
     * Polyfill notifications to be readed by legacy clients
     */
    protected function polyfillResponse($notifications)
    {
        //if (!is_array($notifications)) {
        //    return $notifications;
       // }
        $return = [];
        // Formatting for legacy notification handling in frontend
        foreach ($notifications as $key => $entity) {
            $entityObj = Entities\Factory::build($entity->getEntityGuid());
            $fromObj = Entities\Factory::build($entity->getFromGuid());
            $toObj = Core\Session::getLoggedInUser();
            $data = $entity->getData();

            if ($entity->getEntityGuid() && !$entityObj) {
                unset($notifications[$key]);
                continue;
            }

            if ($entity->getFromGuid() && !$fromObj) {
                unset($notifications[$key]);
                continue;
            }

            $notification = [
                'guid' => $entity->getUUID(),
                'uuid' => $entity->getUUID(),
                'description' => $data['description'],
                'entityObj' => $entityObj ? $entityObj->export() : null,
                'filter' => $entity->getType(),
                'fromObj' => $fromObj ? $fromObj->export() : null,
                'from_guid' => $entity->getFromGuid(),
                'to' => $toObj ? $toObj->export() : null,
                'guid' => $entity->getUUID(),
                'notification_view' => $entity->getType(),
                'params' => $data, // possibly some deeper polyfilling needed here,
                'time_created' => $entity->getCreatedTimestamp(),
            ];

            $notification['entity'] = $notification['entityObj'];

            $notification['owner'] =
            $notification['ownerObj'] =
            $notification['from'] =
            $notification['fromObj'];

            if ($entityObj && $entityObj->getType() == 'comment') {
                $parent = Entities\Factory::build($data['parent_guid']);
                if ($parent) {
                    $notification['params']['parent'] = $parent->export();
                }
            }

            if ($notification['params']['group_guid']) {
                $group = Entities\Factory::build($notification['params']['group_guid']);
                if (!$group) {
                    unset($notifications[$key]);
                    continue;
                }
                $notification['params']['group'] = $group->export();
            }

            $return[$key] = $notification;
        }

        return array_values($return);
    }
}