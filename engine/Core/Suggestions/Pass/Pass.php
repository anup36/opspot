<?php
/**
 * Pass model
 */
namespace Opspot\Core\Suggestions\Pass;

use Opspot\Traits\MagicAttributes;

/**
 * @method Pass getSuggestedGuid(): int
 * @method Pass getUserGuid(): int
 */
class Pass
{
    use MagicAttributes;

    /** @var int $suggestedGuid */
    protected $suggestedGuid;

    /** @var int $userGuid */
    protected $userGuid;

}
