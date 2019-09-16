<?php
/**
 * Opspot Admin: User Reports
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Opspot\Controllers\api\v1\admin\reports;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class appeals implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing appeal'
            ]);
        }

        /** @var Core\Reports\Appeals $appeals */
        $appeals = Di::_()->get('Reports\Appeals');

        try {
            $done = $appeals
                ->approve($pages[0]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        if (!$done) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error saving appeal'
            ]);
        }

        return Factory::response([ 'done' => true ]);
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing appeal'
            ]);
        }

        /** @var Core\Reports\Appeals $appeals */
        $appeals = Di::_()->get('Reports\Appeals');

        try {
            $done = $appeals
                ->reject($pages[0]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        if (!$done) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error saving appeal'
            ]);
        }

        return Factory::response([ 'done' => true ]);
    }
}
