<?php

namespace Opspot\Core;

use Opspot\Core\Di\Di;

/**
 * Core Opspot Engine.
 */
class Opspot extends base
{
    public $root = __OPSPOT_ROOT__;
    public $legacy_lib_dir = '/lib/';
    public static $booted = false;

    private $modules = [
        Email\Module::class,
        Experiments\Module::class,
        VideoChat\Module::class,
        Helpdesk\Module::class,
        Subscriptions\Module::class,
    ];

    /**
     * Initializes the site.
     */
    public function init()
    {
        $this->initModules();
        $this->initProviders();
    }

    /**
     * Register our modules.
     */
    public function initModules()
    {
        $modules = [];
        foreach ($this->modules as $module) {
            $modules[] = new $module();
        }

        /*
         * Initialise the modules
         */
        foreach ($modules as $module) {
            $module->onInit();
        }
    }

    /**
     * Register our DI providers.
     */
    public function initProviders()
    {
        Di::_()->bind('Guid', function ($di) {
            return new GuidBuilder();
        }, ['useFactory' => true]);

        (new \Opspot\Entities\EntitiesProvider())->register();
        (new Config\ConfigProvider())->register();
        (new OAuth\OAuthProvider())->register();
        (new Sessions\SessionsProvider())->register();
        (new Boost\BoostProvider())->register();
        (new Data\DataProvider())->register();
        (new Events\EventsProvider())->register();
        //(new Core\Notification\NotificationProvider())->register();
        (new Pages\PagesProvider())->register();
        (new Payments\PaymentsProvider())->register();
        (new Queue\QueueProvider())->register();
        (new Security\SecurityProvider())->register();
        (new Http\HttpProvider())->register();
        (new Translation\TranslationProvider())->register();
        (new Categories\CategoriesProvider())->register();
        (new ThirdPartyNetworks\ThirdPartyNetworksProvider())->register();
        (new I18n\I18nProvider())->register();
        (new Storage\StorageProvider())->register();
        (new Monetization\MonetizationProvider())->register();
        (new Programs\ProgramsProvider())->register();
        (new Wire\WireProvider())->register();
        (new Trending\TrendingProvider())->register();
        (new Media\MediaProvider())->register();
        (new Notification\NotificationProvider())->register();
        (new Reports\ReportsProvider())->register();
        (new Groups\GroupsProvider())->register();
        (new Search\SearchProvider())->register();
        (new Votes\VotesProvider())->register();
        (new Features\FeaturesProvider())->register();
        (new SMS\SMSProvider())->register();
        (new Blockchain\BlockchainProvider())->register();
        (new Payments\Subscriptions\SubscriptionsProvider())->register();
        (new Faq\FaqProvider())->register();
        (new Rewards\RewardsProvider())->register();
        (new Plus\PlusProvider())->register();
        (new Hashtags\HashtagsProvider())->register();
        (new Feeds\FeedsProvider())->register();
    }

    /**
     * Start the Opspot engine.
     */
    public function start()
    {
        $this->checkInstalled();

        $this->loadConfigs();
        $this->loadLegacy();
        $this->loadEvents();

        /*
        * If this is a multisite, then load the specific database settings
        */
        if ($this->detectMultisite()) {
            new multisite();
        }
    }

    /*
    * Load events
    */
    public function loadEvents()
    {
        Events\Defaults::_();
        /*
         * Boot the system, @todo this should be oop?
         */
        \elgg_trigger_event('boot', 'system');

        /*
         * Complete the boot process for both engine and plugins
         */
        elgg_trigger_event('init', 'system');

        /*
         * tell the system that we have fully booted
         */
        self::$booted = true;

        /*
         * System loaded and ready
         */
        \elgg_trigger_event('ready', 'system');
    }

    /**
     * Load settings files.
     */
    public function loadConfigs()
    {
        global $CONFIG;
        if (!isset($CONFIG)) {
            $CONFIG = static::$di->get('Config');
        }

        // Load the system settings
        if (file_exists(__OPSPOT_ROOT__.'/settings.php')) {
            include_once __OPSPOT_ROOT__.'/settings.php';
        }

        // Load mulit globals if set
        if (file_exists(__OPSPOT_ROOT__.'/multi.settings.php')) {
            define('multisite', true);
            require_once __OPSPOT_ROOT__.'/multi.settings.php';
        }
    }

    /**
     * Load the legacy files for Elgg framework.
     *
     * @todo Deprecate this
     */
    public function loadLegacy()
    {
        // TODO: Remove when no longer needed
        $lib_files = array(
            'elgglib.php',
            'access.php',
            'configuration.php',
            'entities.php',
            'extender.php',
            'filestore.php',
            //'group.php',
            'input.php',
            'languages.php',
            'memcache.php',
            //'notification.php',
            'objects.php',
            //'pagehandler.php',
            //'pageowner.php',
            'pam.php',
            //'plugins.php',
            'private_settings.php',
            'sessions.php',
            'sites.php',
            'user_settings.php',
            'users.php',
            //'xml.php',
            //'xml-rpc.php'
        );

        foreach ($lib_files as $file) {
            $file = __OPSPOT_ROOT__.$this->legacy_lib_dir.$file;
            if (!include_once($file)) {
                $msg = "Could not load $file";
                throw new \InstallationException($msg);
            }
        }
    }

    /**
     * Detects if there are multisite settings present.
     *
     * @return bool
     */
    public function detectMultisite()
    {
        if (file_exists(__OPSPOT_ROOT__.'/multi.settings.php')) {
            return true;
        }

        return false;
    }

    /**
     * Check if Opspot is installed, if not redirect to install script.
     */
    public function checkInstalled()
    {
        /*
         * If we are a multisite, we get the install status from the multisite settings
         */
        if ($this->detectMultisite()) {
            //we do this on db load.. not here
        } else {
            if (!file_exists(__OPSPOT_ROOT__.'/settings.php') && !defined('__OPSPOT_INSTALLING__')) {
                header('Location: install.php');
                exit;
            }
        }
    }

    /**
     * TBD. Not used.
     *
     * @return bool
     */
    public static function getVersion()
    {
        return false;
    }
}
