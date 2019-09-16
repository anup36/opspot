<?php
/**
 * Opspot FS - pseudo router
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Opspot\Controllers\fs;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Fs\Factory;

class fs implements Interfaces\Fs
{
    public function get($pages)
    {
        return Factory::build($pages);
    }
}
