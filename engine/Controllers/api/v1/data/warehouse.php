<?php
/**
 * Opspot  Data Warehouse Runner
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1\data;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class warehouse implements Interfaces\Api
{
    /**
     * Data warehouse
     *
     * API:: /v1/data/warehouse
     */
    public function get($pages)
    {
        $start = microtime();
        \Opspot\Core\Data\Warehouse\Factory::build(array_shift($pages))->run($pages);
        $end = microtime();
        
        return Factory::response(array('took'=>$end-$start));
    }
    
    public function post($pages)
    {
    }
    
    public function put($pages)
    {
        return Factory::response(array());
    }
    
    public function delete($pages)
    {
        $activity = new Entities\Activity($pages[0]);
        if (!$activity->guid) {
            return Factory::response(array('status'=>'error', 'message'=>'could not find activity post'));
        }
 
        return Factory::response(array());
    }
}
