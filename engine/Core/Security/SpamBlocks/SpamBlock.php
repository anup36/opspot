<?php
/**
 * Spam Block Model
 */
namespace Opspot\Core\Security\SpamBlocks;

use Opspot\Traits\MagicAttributes;

class SpamBlock
{

    use MagicAttributes;

    /** @var $key */
    private $key;

    /** @var $value */
    private $value;

}
