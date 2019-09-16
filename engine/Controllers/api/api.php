<?php
/**
 * Opspot API - pseudo router
 *
 * @version 1
 * @author Mark Harding
 *
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host="www.ops.doesntexist.com",
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0",
 *         title="Opspot",
 *         description="",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(
 *             email="apiteam@wordnik.com"
 *         ),
 *         @SWG\License(
 *             name="To be confirmed",
 *             url="http://www.opspot.org/"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Opspot",
 *         url="http://www.opspot.org"
 *     )
 * )
 * @SWG\SecurityScheme(
 *   securityDefinition="opspot_oauth2",
 *   type="oauth2",
 *   authorizationUrl="https://www.ops.doesntexist.com/oauth2/authorize",
 *   flow="implicit",
 *   scopes={
 *   }
 * )
 * @SWG\Info(title="Opspot Public API", version="1.0")
 */
namespace Opspot\Controllers\api;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class api implements Interfaces\Api
{

    /** @var Request $request **/
    private $request;

    /** @var Response $response **/
    private $response;

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function get($pages)
    {
        return Factory::build($pages, $this->request, $this->response);
    }
    
    public function post($pages)
    {
        return Factory::build($pages, $this->request, $this->response);
    }
    
    public function put($pages)
    {
        return Factory::build($pages, $this->request, $this->response);
    }
    
    public function delete($pages)
    {
        return Factory::build($pages, $this->request, $this->response);
    }
}
