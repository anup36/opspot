<?php
/**
 * Opspot Logout Endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class logout implements Interfaces\Api
{
    public function get($pages)
    {
    }
    
    /**
     * Logout
     * @param $pages
     *
     * @SWG\Post(
     *     summary="Logout",
     *     path="/v1/logout",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        error_log("logout request received");
        $db = new Core\Data\Call('entities');
        $db->removeAttributes(Core\Session::getLoggedinUser()->guid, array('surge_token'));

        //remove the oauth access token
        \opspot\plugin\oauth2\storage::remove($_POST['access_token']);
        return Factory::response([]);
    }

    public function put($pages)
    {
    }
    public function delete($pages)
    {
    }
}
