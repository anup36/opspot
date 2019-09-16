<?php
/**
 * Helpdesk vote entity
 */
namespace Opspot\Core\Helpdesk\Question\Votes;

use Opspot\Api\Factory;
use Opspot\Traits\MagicAttributes;

class Vote
{
    use MagicAttributes;

    /** @var string $questionUuid */
    protected $questionUuid;

    /** @var int $userGuid */
    protected $userGuid;

    /** @var string $direction */
    protected $direction;

}
