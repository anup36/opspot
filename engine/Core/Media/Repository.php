<?php
namespace Opspot\Core\Media;

use Opspot\Core;
use Opspot\Core\Security;
use Opspot\Entities;

class Repository
{
    public function getEntity($guid)
    {
        if (!$guid) {
            return false;
        }

        $entity = Core\Entities::build(new Entities\Entity($guid));

        if (!$entity || !$entity->guid || !Security\ACL::_()->read($entity)) {
            return false;
        }

        return $entity;
    }

    public function delete($guid)
    {
        if (!$guid) {
            return false;
        }

        $entity = Entities\Factory::build($guid);

        if (!$entity || !$entity->guid || !$entity->canEdit()) {
            return false;
        }

        $entity->delete();

        return true;
    }
}
