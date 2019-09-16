<?php
/**
 * Opspot Session
 */
namespace Opspot\Core\Sessions;

use Opspot\Traits\MagicAttributes;

class Session
{

    use MagicAttributes;

    /** @var string $id */
    private $id;

    /** @var string $token */
    private $token;

    /** @var int $userGuid */
    private $userGuid;

    /** @var int $expires */
    private $expires;

}
