<?php
/**
 * Opspot Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Core\Security;
use Opspot\Core\Queue;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Helpers;
use Opspot\Core\Subscriptions;

class subscribe implements Interfaces\Api
{
    /**
     * Returns the entities
     * @param array $pages
     *
     * API:: /v1/subscribe/subscriptions/:guid or /v1/subscribe/subscribers/:guid
     */
    public function get($pages)
    {
        $response = array();

        switch ($pages[0]) {
            case 'subscriptions':
                $db = new \Opspot\Core\Data\Call('friends');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                if (!$subscribers) {
                    return Factory::response([]);
                }
                $users = array();
                foreach ($subscribers as $guid => $subscriber) {
                    if ($guid == get_input('offset')) {
                        continue;
                    }
                    if (is_numeric($subscriber)) {
                        //this is a local, old style subscription
                        $users[] = new \Opspot\Entities\User($guid);
                        continue;
                    }

                    $users[] = new \Opspot\Entities\User(json_decode($subscriber, true));
                }

                $users = array_values(array_filter($users, function ($user) {
                    return ($user->enabled != 'no' && $user->banned != 'yes');
                }));
                
                $response['users'] = factory::exportable($users);
                $response['load-next'] = (string) end($users)->guid;
                $response['load-previous'] = (string) key($users)->guid;
                break;
            case 'subscribers':

                if ($pages[1] == "100000000000000519") {
                    break;
                }

                $db = new \Opspot\Core\Data\Call('friendsof');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                if (!$subscribers) {
                    return Factory::response([]);
                }
                $users = array();
                if (get_input('offset') && key($subscribers) != get_input('offset')) {
                    $response['load-previous'] = (string) get_input('offset');
                } else {
                    foreach ($subscribers as $guid => $subscriber) {
                        if ($guid == get_input('offset')) {
                            unset($subscribers[$guid]);
                            continue;
                        }
                        if (is_numeric($subscriber)) {
                            //this is a local, old style subscription
                            $users[] = new \Opspot\Entities\User($guid);
                            continue;
                        }

                        //var_dump(print_r($users,true));die();
                        $users[] = new \Opspot\Entities\User(json_decode($subscriber, true));
                    }

                    $users = array_values(array_filter($users, function ($user) {
                        return ($user->enabled != 'no' && $user->banned != 'yes');
                    }));

                    $response['users'] = factory::exportable($users);
                    $response['load-next'] = (string) end($users)->guid;
                    $response['load-previous'] = (string) key($users)->guid;
                }
                break;
        }

        return Factory::response($response);
    }

    /**
     * Subscribes a user to another
     * @param array $pages
     *
     * API:: /v1/subscriptions/:guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        if ($pages[0] === 'batch') {
            $guids = $_POST['guids'];

            //temp: captcha tests
            if (Core\Session::getLoggedInUser()->captcha_failed) {
                return Factory::response(['status' => 'error']);
            }

            Queue\Client::build()
              ->setQueue('SubscriptionDispatcher')
              ->send([
                  'currentUser' => Core\Session::getLoggedInUser()->guid,
                  'guids' => $guids
              ]);

            return Factory::response(['status' => 'success']);
        }

        $publisher = Entities\Factory::build($pages[0]);

        $canSubscribe = Security\ACL::_()->interact(Core\Session::getLoggedinUser(), $pages[0]) &&
            Security\ACL::_()->interact($pages[0], Core\Session::getLoggedinUser(), 'subscribe');

        if (!$canSubscribe) {
            return Factory::response([
                'status' => 'error'
            ]);
        }


        $manager = new Subscriptions\Manager();
        $subscription = $manager->setSubscriber(Core\Session::getLoggedinUser())
            ->subscribe($publisher);

        $response = [];
        if (!$subscription) {
            $response = [
                'status' => 'error',
                'message' => 'Subscribing failed',
            ];
        }

        //TODO: move Core/Subscriptions/Delegates
        $event = new Core\Analytics\Metrics\Event();
        $event->setType('action')
            ->setAction('subscribe')
            ->setProduct('platform')
            ->setUserGuid((string) Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $pages[0])
            ->push();

        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();
        $publisher = Entities\Factory::build($pages[0]);

        $manager = new Subscriptions\Manager();
        $subscription = $manager->setSubscriber(Core\Session::getLoggedinUser())
            ->unSubscribe($publisher); 

        $event = new Core\Analytics\Metrics\Event();
        $event->setType('action')
            ->setAction('unsubscribe')
            ->setProduct('platform')
            ->setUserGuid((string) Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $pages[0])
            ->push();

        $response = array('status'=>'success');
        if (!$subscription) {
            $response = array(
                'status' => 'error'
            );
        }

        return Factory::response($response);
    }
}
