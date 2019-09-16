<?php
/**
 * Opspot boost pages
 */
namespace Opspot\Controllers\Legacy;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Helpers;

class search extends Core\page implements Interfaces\page
{
    public function get($pages)
    {
        $this->forward('api/v1/search?q=' . $_GET['q']);
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
