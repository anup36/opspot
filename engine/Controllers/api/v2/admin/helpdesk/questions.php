<?php

namespace Opspot\Controllers\api\v2\admin\helpdesk;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Core\Helpdesk\Entities\Question;
use Opspot\Core\Helpdesk\Repository;
use Opspot\Interfaces\Api;
use Opspot\Interfaces\ApiAdminPam;

class questions implements Api, ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $question = null;
        $answer = null;
        $category_uuid = null;

        if (!isset($_POST['question'])) {
            return Factory::response(['status' => 'error', 'message' => 'question must be provided']);
        }

        $question = $_POST['question'];

        if (!isset($_POST['answer'])) {
            return Factory::response(['status' => 'error', 'message' => 'answer must be provided']);
        }

        $answer = $_POST['answer'];

        if (!isset($_POST['category_uuid'])) {
            return Factory::response(['status' => 'error', 'message' => 'category_uuid must be provided']);
        }

        $category_uuid = $_POST['category_uuid'];
        $uuid = $_POST['uuid'] ?? null;

        $entity = Di::_()->get('Helpdesk\Question');
        $entity
            ->setUuid($uuid)
            ->setQuestion($question)
            ->setAnswer($answer)
            ->setCategoryUuid($category_uuid)
            ->setPosition($_POST['position'] ?? 10);

        /** @var \Opspot\Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $uuid = $manager->add($entity);

        return Factory::response([
            'status' => 'success',
            'uuid' => $uuid,

        ]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $question_uuid = null;

        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'question_uuid must be provided']);
        }
        
        $question_uuid =  $pages[0];

        /** @var \Opspot\Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $done = $manager->delete($question_uuid);

        return Factory::response([
            'status' => 'success',
            'done' => $done
        ]);
    }

}
