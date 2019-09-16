<?php
/**
 * Helpdesk single questions endpoint
 */
namespace Opspot\Controllers\api\v2\helpdesk\questions;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Core\Helpdesk\Question\Manager;
use Opspot\Core\Helpdesk\Question\Repository;
use Opspot\Core\Session;
use Opspot\Interfaces\Api;

class question implements Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'uuid must be provided']);
        }

        $uuid = $pages[0];

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $question = $manager->get($uuid);

        return Factory::response([
            'status' => 'success',
            'question' => $question->export()
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
