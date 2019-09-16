<?php
/**
 * Opspot Admin: Ban
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Opspot\Controllers\api\v1\admin;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Config;

class ban implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Ban a user
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        $user->ban_reason = $data['note'] ?: $data['subject']['label'];

        $user->banned = 'yes';
        $user->code = '';
        $user->save();

        \cache_entity($user);

        (new Core\Data\Sessions())->destroyAll($user->guid);

        try {
            $params = [
                'index' => Config::_()->elasticsearch['index'],
                'type' => 'user',
                'id' => $user->guid
            ];

            /** @var Core\Data\ElasticSearch\Client $elastic */
            $elastic = Di::_()->get('Database\ElasticSearch');

            $elastic->getClient()->delete($params);

        } catch (\Exception $e) {
            error_log(print_r($e->getMessage()));
        }

        Dispatcher::trigger('ban', 'user', $user);
        
        return Factory::response([
            'done' => true
        ]);
    }

    /**
     * Unban a user
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }
        
        $user->banned = 'no';
        $user->save();

        \cache_entity($user);

        return Factory::response([
            'done' => true
        ]);
    }
}
