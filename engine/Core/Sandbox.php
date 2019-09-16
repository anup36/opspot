<?php
namespace Opspot\Core;

use Opspot\Core\Di\Di;
use Opspot\Entities as Entity;

class Sandbox
{
    public static function user($default, $sandbox = 'default')
    {
        $config = Di::_()->get('Config')->get('sandbox');

        if (!$config) {
            return $default;
        }

        if (!$config['enabled']) {
            return $default;
        }

        $guid = $config[$sandbox]['guid'];
        error_log(json_encode($config));

        error_log(':: [Sandbox] Sandboxing user ' . $guid);
        return new Entity\User($guid);
    }
}
