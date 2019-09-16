<?php
/**
 * Opspot OAuth user
 */
namespace Opspot\Core\OAuth\Entities;

use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class UserEntity implements UserEntityInterface
{
    use EntityTrait;
}
