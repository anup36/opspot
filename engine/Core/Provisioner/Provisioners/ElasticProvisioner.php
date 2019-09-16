<?php

namespace Opspot\Core\Provisioner\Provisioners;

use Opspot\Core\Di\Di;
use Elasticsearch;

class ElasticProvisioner implements ProvisionerInterface
{
    protected $config;

    public function provision(bool $cleanData)
    {
        $config = Di::_()->get('Config')->elasticsearch;

        $client = Elasticsearch\ClientBuilder::create()
            ->setHosts($config['hosts'])
            ->build();


				$mdata = json_decode(file_get_contents(dirname(__FILE__) . '/ElasticSearchIndices/opspot_badger.json'), false);
        // index
        $data = [
            'index' => $config['index'],
            'body' => [
                'settings' => [ 
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                 ],
                 'mappings' => $mdata->mappings
             ],
        ];
        $response = $client->indices()->create($data);
				// print_r($response);

        // metrics_index
        $mdata = json_decode(file_get_contents(dirname(__FILE__) . '/ElasticSearchIndices/opspot-metrics-mm-YYYYY.json'), false);
        $data = [
            'index' => $config['metrics_index'],
            'body' => [
                'settings' => [ 
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                 ],
                 'mappings' => $mdata->mappings
             ],
        ];
        $response = $client->indices()->create($data);
				// print_r($response);
				
    }
}
