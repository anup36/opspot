<?php


namespace Opspot\Controllers\api\v2\admin\helpdesk;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Core\Helpdesk\Category\Category;
use Opspot\Core\Helpdesk\Repository;
use Opspot\Interfaces\Api;
use Opspot\Interfaces\ApiAdminPam;

class categories implements Api, ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        /** @var \Opspot\Core\Helpdesk\Category\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Category\Manager');

        try {
            $title = $this->getParam('title', 'title must be provided');
            
            $parent_uuid = $this->getParam('parent_uuid');

            $entity = new Category();
            $entity->setTitle($title)
                ->setParentUuid($parent_uuid);

            $manager->add($entity);

        } catch (\Exception $e) {
            return Factory::response(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $category_uuid = $pages[0];

        if (!$category_uuid) {
            return Factory::response(['status' => 'error', 'message' => 'category_uuid must be provided']);
        }

        /** @var \Opspot\Core\Helpdesk\Category\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Category\Manager');

        $done = $manager->delete($category_uuid);

        return Factory::response([
            'status' => 'success',
            'done' => $done
        ]);
    }

    protected function getParam($param, $error = null)
    {
        if (!isset($_POST[$param]) && $error) {
            throw new \Exception($error);
        }
        return $_POST[$param] ?: null;
    }

}
