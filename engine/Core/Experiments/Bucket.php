<?php
/**
 * Experiments manager
 */
namespace Opspot\Core\Experiments;

use Opspot\Interfaces\ModuleInterface;
use Opspot\Traits\MagicAttributes;

class Bucket
{
    use MagicAttributes;

    /** @param string $id */
    private $id;

    /** @param int $weight */
    private $weight = 0;

}
