<?php
/**
 * Opspot Webhook: Braintree
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1\webhooks;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Helpers;
use Opspot\Core\Payments;

use Braintree_WebhookNotification;

class braintree implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
   * NOT AVAILABLE
   */
  public function get($pages)
  {
      return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
  }

  /**
   */
  public function post($pages)
  {
      error_log("[webhooks]:: hit first entrace point");

      $gateway = isset($pages[0]) ? $pages[0] : 'default';

      $bt = Payments\Factory::build('braintree', ['gateway'=>$gateway]);

      $hooks = new Payments\Hooks();
      $hooks->loadDefaults();

      $webhooks = new Payments\Braintree\Webhooks($hooks, $bt);
      $webhooks->setSignature($_POST['bt_signature'])
        ->setPayload($_POST['bt_payload'])
        ->run();
  }


    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
