<?php

namespace Opspot\Controllers\api\v2\admin;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Session;
use Opspot\Interfaces;

class email implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        $response = [];
        if (isset($pages[0]) && $pages[0] === 'previsualize') {
            $template = new Core\Email\Template();
            $template
                ->setTemplate()
                ->setBody($_GET['code'] ?: '', false)
                ->toggleMarkdown(true)
                ->set('user', Session::getLoggedinUser())
                ->set('username', Session::getLoggedinUser()->username);
            $response = ['content' => $template->render()];
        }
        Factory::response($response);
    }

    public function post($pages)
    {
        Factory::response([]);
    }

    public function put($pages)
    {
        Factory::response([]);
    }

    public function delete($pages)
    {
        Factory::response([]);
    }

}
