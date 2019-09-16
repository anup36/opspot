<?php

namespace Opspot\Core\Provisioner\Provisioners;

use Opspot\Core\Di\Di;
use MongoDB\BSON\ObjectID;
use MongoDB\Client as MongoClient;

class MongoProvisioner implements ProvisionerInterface
{
    protected $config;

    public function provision(bool $cleanData)
    {
				// check
        if (!class_exists('\MongoDB\Driver\Manager')) {
            throw new \Exception("MongoDB Driver is not installed");
        }

        if (!class_exists('\MongoDB\Client')) {
            throw new \Exception("MongoDB Client is not installed");
        }

        $config = Di::_()->get('Config')->mongodb;
        $server = isset($config['server']) ? $config['server'] : 'mongo';
        $dbname = isset($config['db']) ? $config['db'] : 'opspot';

    }
}
