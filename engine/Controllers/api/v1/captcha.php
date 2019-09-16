<?php
/**
 * Opspot Captcha API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class captcha implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Get a captcha question
     *
     */
    public function get($pages)
    {
        $captcha = Core\Di\Di::_()->get('Security\Captcha');

        $response = [
          'question' => $captcha->getQuestion()
        ];

        return Factory::response($response);
    }


    public function post($pages)
    {
        $captcha = Core\Di\Di::_()->get('Security\Captcha');

        $success = $captcha->validateAnswer($_POST['type'], $_POST['question'], $_POST['answer'], $_POST['nonce'], $_POST['hash']);
        return Factory::response([ 'success' => $success ]);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
