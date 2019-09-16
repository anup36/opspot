<?php

namespace Opspot\Controllers\api\v2\helpdesk\questions;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Core\Helpdesk\Question\Manager;
use Opspot\Interfaces\Api;

class search implements Api
{
    public function get($pages)
    {
        $limit = 5;

        if (isset($_GET['limit'])) {
            $limit = intval($_GET['limit']);
        }

        $offset = 0;

        if (isset($_GET['offset'])) {
            $offset = intval($_GET['offset']);
        }

        $q = null;

        if (isset($_GET['q']) && trim($_GET['q']) !== '') {
            $q = trim($_GET['q']);
        }

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $questions = $manager->suggest([
            'limit' => $limit,
            'offset' => $offset,
            'q' => $q
        ]);

        return Factory::response([
            'status' => 'success',
            'entities' => Factory::exportable($questions)
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
