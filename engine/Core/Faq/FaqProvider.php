<?php

/**
 * Opspot FAQ Provider
 *
 * @author Mark Harding
 */

namespace Opspot\Core\Faq;

use Opspot\Core\Di\Provider;

class FaqProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Faq', function ($di) {
            return new Manager();
        }, [ 'useFactory'=> true ]);
    }
}
