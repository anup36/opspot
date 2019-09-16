<?php
/**
 * Opspot Banners FS endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\fs\v1;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class pages implements Interfaces\FS
{
    public function get($pages)
    {
        $path = $pages[0];
        $fs = Di::_()->get('Storage');
        $dir = Di::_()->get('Config')->get('staticStorageFolder') ?: 'pages';

        $fs->open("$dir/page_banners/{$path}.jpg", 'redirect');
        $fs->read();
    }
}
