<?php
/**
 * Opspot boost pages
 */
namespace Opspot\Controllers\Legacy;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Helpers;

class wall extends core\page implements Interfaces\page
{
    public function get($pages)
    {
        forward('fs/v1/thumbnail/' . $pages[1]);
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
