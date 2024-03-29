<?php

namespace Spec\Opspot\Core\Provisioner;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Opspot;
use Opspot\Entities\Site;

class InstallerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Provisioner\Installer');
    }

    function let(Opspot $opspot) {
        global $CONFIG;

        $this->setApp($opspot);

        $this->setOptions([
            'overwrite-settings' => true,
            'domain' => 'phpspec.opspot.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@opspot.io',
            'site-name' => 'PHPSpec Opspot',
            'site-email' => 'phpspec@opspot.io',
            'cassandra-server' => '127.0.0.1',
            'elasticsearch-server' => 'http://localhost',
            'email-public-key' => __FILE__,
            'email-private-key' => __FILE__,
            'phone-number-public-key' => __FILE__,
            'phone-number-private-key' => __FILE__
        ]);

        $CONFIG->site_name = 'PHPSpec Opspot';
    }

    function it_should_check_options_valid()
    {
        $this
            ->shouldNotThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_domain()
    {
        $this->setOptions([
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => '!@#!$asdasd%!%.com!',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_username()
    {
        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'password' => 'phpspec1',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
        
        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'username' => 'foo.bar$',
            'password' => 'phpspec1',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_password()
    {
        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'username' => 'phpspec',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'username' => 'phpspec',
            'password' => '000',
            'email' => 'phpspec@opspot.io',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_check_options_invalid_email()
    {
        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();

        $this->setOptions([
            'domain' => 'phpspec.opspot.io',
            'username' => 'phpspec',
            'password' => 'phpspec1',
            'email' => 'asldkj!@#!@#...net)',
        ]);

        $this
            ->shouldThrow('Opspot\\Exceptions\\ProvisionException')
            ->duringCheckOptions();
    }

    function it_should_build_config()
    {
        $this
            ->shouldNotThrow('\\ProvisionException')
            ->duringBuildConfig([ 'returnResult' => true ]);
    }

    function it_should_setup_site(Site $site)
    {
        $site->set('name', 'PHPSpec Opspot')->shouldBeCalled();
        $site->set('url', 'https://phpspec.opspot.io/')->shouldBeCalled();
        $site->set('access_id', 2)->shouldBeCalled();
        $site->set('email', 'phpspec@opspot.io')->shouldBeCalled();

        $site->save()->willReturn(true)->shouldBeCalled();

        $this
            ->shouldNotThrow('\\ProvisionException')
            ->duringSetupSite($site);
    }

    function it_should_get_site_url()
    {
        $this->getSiteUrl()->shouldReturn('https://phpspec.opspot.io/');
    }
}
