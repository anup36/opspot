<?php
/**
 * Opspot Security Provider
 */

namespace Opspot\Core\Security;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Di\Provider;

class SecurityProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Security\ACL\Block', function ($di) {
            return new ACL\Block(
              Di::_()->get('Database\Cassandra\Indexes'),
              Di::_()->get('Database\Cassandra\Cql'),
              Core\Data\cache\factory::build()
            );
        }, ['useFactory'=>true]);

        $this->di->bind('Security\Captcha', function ($di) {
            return new Captcha(Di::_()->get('Config'));
        }, ['useFactory'=>true]);

        $this->di->bind('Security\ReCaptcha', function ($di) {
            return new ReCaptcha(Di::_()->get('Config'));
        }, ['useFactory'=>true]);

        $this->di->bind('Security\TwoFactor', function ($di) {
            return new TwoFactor();
        }, ['useFactory'=>false]);

        $this->di->bind('Security\LoginAttempts', function ($di) {
            return new LoginAttempts();
        }, ['useFactory' => false]);

        $this->di->bind('Security\Password', function ($di) {
            return new Password();
        }, ['useFactory' => false]);

        $this->di->bind('Security\Spam', function ($di) {
            return new Spam();
        }, ['useFactory' => true]);

        $this->di->bind('Security\Events', function ($di) {
            return new Events();
        }, ['useFactory' => true]);

        $this->di->bind('Security\SpamBlocks\IPHash', function ($di) {
            return new SpamBlocks\IPHash;
        }, ['useFactory' => true]);
    }
}
