<?php
namespace Opspot\Core\Provisioner;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Entities\Site;
use Opspot\Entities\Activity;
use Opspot\Exceptions\ProvisionException;
use Opspot\Helpers;

class Installer
{
    protected $app;

    protected $defaults = [];
    protected $options = [];

    public function __construct()
    {
        $this->defaults = [
            'domain' => 'http://localhost:8080',
            'username' => 'opspot',
            'password' => 'Pa$$w0rd',
            'email' => 'opspot@ops.doesntexist.com',
            'email-private-key' => '/.dev/opspot.pem',
            'email-public-key' => '/.dev/opspot.pub',
            'phone-number-private-key' => '/.dev/opspot.pem',
            'phone-number-public-key' => '/.dev/opspot.pub',
            'cassandra-keyspace' => 'opspot',
            'cassandra-server' => 'cassandra',
            'cassandra-replication-factor' => '3',
            'dataroot' => '/data/',
            'default-site' => 1,
            'cache-path' => '/tmp/opspot-cache/',
            'queue-exchange' => 'opspotqueue',
            'facebook-app-id' => '',
            'facebook-app-secret' => '',
            'twitter-app-id' => '',
            'twitter-app-secret' => '',
            'twilio-account-sid' => '',
            'twilio-auth-token' => '',
            'twilio-from' => '',
            'google-api-key' => '',
            'apple-sandbox-enabled' => 0,
            'apple-certificate' => '',
            'site-name' => 'Opspot',
            'no-https' => false,
            'sns-secret' => '',
						'rabbitmq-host' => 'rabbitmq',
						'rabbitmq-port' => '',
						'rabbitmq-username' => 'guest',
						'rabbitmq-password' => 'guest',
						'kickbox-secret' => '',
						'mongodb-servers' => '',
						'mongodb-options' => '',
						'mongodb-driver-options' => '',
						'mongodb-db' => 'opspot',
						'elasticsearch-hosts' => ['http://localhost:9200'],
            'elasticsearch-index' => 'opspot_badger',
            'elasticsearch-metrics-index' => 'opspot-metrics',
            'elasticsearch-tags-index' => 'opspot-trending-hashtags',
            'max-video-length' => '60',
            'max-video-length-plus' => '600',
        ];

        usleep(mt_rand(1, 9999));
        $this->defaults['site-secret'] = hash('sha512', openssl_random_pseudo_bytes(128));

        usleep(mt_rand(1, 9999));
        $this->defaults['jwt-secret'] = hash('sha512', openssl_random_pseudo_bytes(128));
    }

    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->defaults, $options);
        return $this;
    }

    // If different checks become necessary for the other components,
    // dispatch the calls here.
    public function checkOptions()
    {
        $isInstallOnly = isset($this->options['only']);
        if (!$isInstallOnly || $this->options['only'] === "site"){
            $this->checkSiteOptions();
            return;
        }
        // TODO: Check all database parameters.
    }

    public function checkSiteOptions()
    {
        if (!isset($this->options['username']) || !$this->options['username']) {
            throw new ProvisionException('Admin username was not provided');
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $this->options['username'])) {
            throw new ProvisionException('Admin username is invalid');
        }

        if (!isset($this->options['password']) || !$this->options['password']) {
            throw new ProvisionException('Admin password was not provided');
        } elseif (strlen($this->options['password']) < 6) {
            throw new ProvisionException('Admin password is too short');
        }

        if (!isset($this->options['email']) || !$this->options['email']) {
            throw new ProvisionException('Admin email was not provided');
        } elseif (!filter_var($this->options['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ProvisionException('Admin email is invalid');
        }

        // REVNOTE: Removing because I don't think use-existing-settings should exclude necessary parameter checks,
        // in case there are errors in the settings.php file.
//        if (isset($this->options['use-existing-settings']) && $this->options['use-existing-settings']) {
//            // Finish checking if we're using the existing settings file
//            return;
//        }

        if (!isset($this->options['domain']) || !$this->options['domain']) {
            throw new ProvisionException('Domain name was not provided');
        } elseif (!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->options['domain'])) {
            //throw new ProvisionException('Domain name is invalid');
        }

        if (!isset($this->options['email-private-key']) || !$this->options['email-private-key']) {
            throw new ProvisionException('Email private key path was not provided');
        } elseif (!is_readable($this->options['email-private-key'])) {
            throw new ProvisionException('Email private key is not readable');
        }

        if (!isset($this->options['email-public-key']) || !$this->options['email-public-key']) {
            throw new ProvisionException('Email public key path was not provided');
        } elseif (!is_readable($this->options['email-public-key'])) {
            throw new ProvisionException('Email public key is not readable');
        }

        if (!isset($this->options['phone-number-private-key']) || !$this->options['phone-number-private-key']) {
            throw new ProvisionException('Phone number private key path was not provided');
        } elseif (!is_readable($this->options['phone-number-private-key'])) {
            throw new ProvisionException('Phone number private key is not readable');
        }

        if (!isset($this->options['phone-number-public-key']) || !$this->options['phone-number-public-key']) {
            throw new ProvisionException('Phone number public key path was not provided');
        } elseif (!is_readable($this->options['phone-number-public-key'])) {
            throw new ProvisionException('Phone number public key is not readable');
        }

        if (isset($this->options['site-email']) && !filter_var($this->options['site-email'], FILTER_VALIDATE_EMAIL)) {
            throw new ProvisionException('Site email is invalid');
        }

    }

    /* Uses settings.example.php as a template to build out settings.php, which is read at
       runtime for every call to the server. Replaced tags take the form {{varName}} such as
       {{dataroot}}. This is essentially a mustache style transformation to generate a runtime
       configuration file (settings.php).
    */
    public function buildConfig(array $flags = [])
    {
        $flags = array_merge([
            'returnResult' => false
        ], $flags);

        $source = $this->app->root . DIRECTORY_SEPARATOR . 'settings.example.php';
        $target = $this->app->root . DIRECTORY_SEPARATOR . 'settings.php';

        if (is_file($target) && !isset($this->options['overwrite-settings'])) {
            throw new ProvisionException('Opspot is already installed');
        }

        $template = file_get_contents($source);

        // Build options
        if (!isset($this->options['path'])) {
            $this->options['path'] = dirname($this->app->root) . DIRECTORY_SEPARATOR;
        }

        if (!isset($this->options['jwt-domain'])) {
            $this->options['jwt-domain'] = $this->options['domain'];
        }

        // Bug REVNOTE. Was resulting in http://localhost:8080:8010
        if (!isset($this->options['socket-server-uri'])) {
            $domain = $this->options['domain'];
            $domainParts = parse_url($domain);
            $this->options['socket-server-uri'] = $domainParts['scheme'] . $domainParts['host'] . ':8010';
        }

        if (!isset($this->options['site-name'])) {
            $this->options['site-name'] = "Opspot";
        }

        if (!isset($this->options['site-email'])) {
            $this->options['site-email'] = $this->options['email'];
        }

        $this->options['apple-sandbox-enabled'] =
            isset($this->options['apple-sandbox-enabled']) && $this->options['apple-sandbox-enabled'] ?
            1 : 0;

        // Inject options

        $result = preg_replace_callback('/\{\{([a-z0-9\-_]+)\}\}/', function ($matches) {
            if (!isset($this->options[$matches[1]])) {
                throw new ProvisionException("Configuration key `{$matches[1]}` is not present on defaults or command line arguments");
            }

            return (string) $this->options[$matches[1]];
        }, $template);

        if ($flags['returnResult']) {
            return $result;
        }

        // Write template
        file_put_contents($target, $result);
    }

    public function checkSettingsFile()
    {
        $target = $this->app->root . DIRECTORY_SEPARATOR . 'settings.php';

        if (!is_file($target)) {
            throw new ProvisionException('Opspot settings file is missing');
        }
    }

    public function setupStorage(Provisioners\ProvisionerInterface $cassandraStorage = null,
                                 Provisioners\ProvisionerInterface $cockroachStorage = null,
                                 $cleanData = false)
    {
        $this->provisionCassandra($cassandraStorage, $cleanData);
        $this->provisionCockroach($cockroachStorage, $cleanData);
    }

    public function provisionCassandra(Provisioners\ProvisionerInterface $cassandraStorage = null,
                                       $cleanData = false) {
        $cassandraStorage = $cassandraStorage ?: new Provisioners\CassandraProvisioner();
        $cassandraStorage->provision($cleanData);
    }

    public function provisionCockroach(Provisioners\ProvisionerInterface $cockroachProvisioner = null,
                                       $cleanData = false) {
        $cockroachProvisioner = $cockroachProvisioner ?: new Provisioners\CockroachProvisioner();
        $cockroachProvisioner->provision($cleanData);
    }

    public function reloadStorage()
    {
        Core\Data\Pool::$pools = [];
    }

		// other storage - start shreos

    public function provisionElastic(Provisioners\ProvisionerInterface $elasticStorage = null,
                                       $cleanData = false) {
        $elasticStorage = $elasticStorage ?: new Provisioners\ElasticProvisioner();
        $elasticStorage->provision($cleanData);
    }

    public function provisionMongo(Provisioners\ProvisionerInterface $mongoProvisioner = null,
                                       $cleanData = false) {
        $mongoProvisioner = $mongoProvisioner ?: new Provisioners\MongoProvisioner();
        $mongoProvisioner->provision($cleanData);
    }

		// other storage - end shreos

    public function setupSite($site = null)
    {
        $config = Di::_()->get('Config');

        $site = $site ?: new Site();
        $site->name = $config->get('site_name');
        $site->url = $this->getSiteUrl();
        $site->access_id = ACCESS_PUBLIC;
        $site->email = isset($this->options['site-email']) && $this->options['site-email'] ? $this->options['site-email'] : $this->options['email'];

        $site->save();
    }

    public function setupFirstAdmin()
    {
        $user = register_user(
            $this->options['username'],
            $this->options['password'],
            $this->options['username'],
            $this->options['email']
        );

        if (!$user) {
            throw new ProvisionException('Cannot create new User entity');
        }

        $user->admin = 'yes';
        $user->validated = true;
        $user->validated_method = 'admin_user';
        $userSaved = $user->save();

        if (!$userSaved) {
            throw new ProvisionException('Cannot grant privileges to new User entity');
        }

        Helpers\Wallet::createTransaction($user->guid, 750000000, $user->guid, 'Installed Opspot');

        $activity = new Activity();
        $activity->owner_guid = $user->guid;
        $activity->setMessage('Hello Opspot!');
        $activitySaved = $activity->save();

        if (!$activitySaved) {
            throw new ProvisionException('Cannot create first Activity entity');
        }
    }

    public function getSiteUrl()
    {
        $config = Di::_()->get('Config');

        if ($config->get('site_url')) {
            $siteUrl = $config->get('site_url');
        } else {
            $siteUrl = $this->options['no-https'] ? 'http' : 'https';
            $siteUrl .= '://' . $this->options['domain'] . '/';
        }

        return $siteUrl;
    }
}
