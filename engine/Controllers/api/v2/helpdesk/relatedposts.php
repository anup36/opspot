<?php
/**
 * Help & Support Group posts search
 */
namespace Opspot\Controllers\api\v2\helpdesk;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Interfaces\Api;

class relatedposts implements Api
{
    public function get($pages)
    {
        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'paramenter q is required'
            ]);
        }

        $limit = 30;

        $search = Di::_()->get('Helpdesk\Search');

        $result = $search->search($_GET['q'], $limit);

        $exported = array_map(function($r) {
            if ($r)
            return $r->export();
        }, $result);

        return Factory::response([
            'status' => 'success',
            'posts' => $exported
        ]);
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
