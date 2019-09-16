<?php
/**
 * Helpdesk Categories Manager
 */
namespace Opspot\Core\Helpdesk\Category;

use Opspot\Core\Di\Di;
use Opspot\Common\Repository\Response;
use Opspot\Core\Helpdesk\Category\Category;

class Manager
{
    /** @var Repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'uuid' => '',
            'recursive' => false,
        ], $opts);
        return $this->repository->getList($opts);
    }

    public function get($uuid)
    {
        return $this->repository->get($uuid);
    }

    public function getBranch($uuid)
    {
        return $this->repository->getBranch($uuid);
    }

    public function add(Category $category)
    {
        return $this->repository->add($category);
    }

    public function delete(string $category_uuid)
    {
        return $this->repository->delete($category_uuid);
    }
}
