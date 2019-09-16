<?php

/**
 * Entities backed by repositories
 *
 * @author emi
 */

namespace Opspot\Entities;

use Opspot\Traits\Exportable;
use Opspot\Traits\MagicAttributes;
use Opspot\Traits\DirtyChecking;

abstract class RepositoryEntity extends LegacyEntityCompat implements \JsonSerializable
{
    use MagicAttributes;
    use Exportable;
    use DirtyChecking;

    /**
     * Returns if the entity can be edited by the current user
     * @param User|null $user
     * @return bool
     */
    abstract public function canEdit(User $user = null);

    /**
     * Specifies the exportable properties
     * @return array<string|\Closure>
     */
    abstract public function getExportable();
}
