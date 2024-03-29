<?php

namespace Opspot\Controllers\Cli\Payments;

use Opspot\Cli;
use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Payments\Subscriptions\Manager;
use Opspot\Core\Payments\Subscriptions\Queue;
use Opspot\Helpers\Cql;
use Opspot\Interfaces;

class Subscriptions extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help($command = null)
    {
        $this->out('Syntax usage: payments subscriptions [run]');
    }

    public function exec()
    {
        $this->help();
    }

    public function run()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Initialize events
        \Opspot\Core\Events\Defaults::_();

        /** @var Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');

        /** @var Queue $queue */
        $subscriptions = Di::_()->get('Payments\Subscriptions\Iterator');
        $subscriptions->setFrom(time())
            ->setPaymentMethod('tokens')
            ->setPlanId('wire');

        foreach ($subscriptions as $subscription) {
            $this->out("Subscription:`{$subscription->getId()}`");
            $billing = date('d-m-Y', $subscription->getNextBilling());
            $user_guid = $subscription->getUser()->guid;
            $this->out("\t$billing | $user_guid");

            if (!$this->getOpt('dry-run')) {
                $this->out("\t CHARGED");
                $manager->setSubscription($subscription);
                $manager->charge();
            }
        }
        
        $this->out("Done");
    }
}
