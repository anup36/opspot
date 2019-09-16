<?php

/**
 * Opspot New Purchase Email Delegate
 *
 * @author mark
 */

namespace Opspot\Core\Blockchain\Purchase\Delegates;

use Opspot\Core\Blockchain\Purchase\Purchase;
use Opspot\Core\Config;
use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Util\BigNumber;
use Opspot\Core\Email\Campaigns\Custom;
use Opspot\Entities\User;

class NewPurchaseEmail
{
    /** @var Config */
    protected $config;

    /** @var Custom */
    protected $campaign;

    public function __construct($config = null, $campaign = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->campaign = $campaign ?: new Custom;
    }

    public function send(Purchase $purchase)
    {
        $amount = (int) BigNumber::_($purchase->getRequestedAmount())->div(10 ** 18)->toString();

        $this->campaign
            ->setUser(new User($purchase->getUserGuid()))
            ->setSubject("Your purchase of $amount Tokens is being processed.")
            ->setTemplate('new-token-purchase.md')
            ->setTopic('billing')
            ->setCampaign('tokens')
            ->setVars([
                'date' => date('d-M-Y', time()),
                'amount' => $amount,
            ])
            ->send();
    }
}
