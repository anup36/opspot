<?php
/**
 * Video chat lease
 */
namespace Opspot\Core\VideoChat\Leases;

use Opspot\Traits\MagicAttributes;

class VideoChatLease
{

    use MagicAttributes;

    /** @var string $key */
    private $key;

    /** @var string $secret */
    private $secret;

    /** @var int $holderGuid  */
    private $holderGuid;

    /** @var int $lastRefreshed */
    private $lastRefreshed;

}
