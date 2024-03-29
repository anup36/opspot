<?php
/**
 * Opspot Core Search Suggest API
 *
 * @version 1
 * @author Emiliano Balbuena
 */

namespace Opspot\Controllers\api\v1\search;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Search\Documents;
use Opspot\Interfaces;

class suggest implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing query'
            ]);
        }

        $suggestions = (new Documents())->suggestQuery($_GET['q']);
        $results = $suggestions['suggest']['autocomplete'][0]['options'];
        foreach ($results as $k => $result) {
            $results[$k]['payload'] = $result['_source'];
        }
        $guids = [];

        if (isset($_GET['access_token'])) {
            foreach ($results as $result) {
                $guids[] = $result['payload']['guid'];
            }
            if ($guids) {
                $response['suggestions'] = Factory::exportable(Core\Entities::get([
                    'guids' => $guids
                ]));
            } else {
                $response['suggestions'] = [];
            }
        } else {
            $response['suggestions'] = $results;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
