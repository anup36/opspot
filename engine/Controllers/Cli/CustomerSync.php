<?php

namespace Opspot\Controllers\Cli;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Cli;
use Opspot\Interfaces;
use Opspot\Exceptions;
use Opspot\Entities;
use Stripe;

class CustomerSync extends Cli\Controller implements Interfaces\CliControllerInterface
{

    private $db;

    public function __construct()
    {
        $this->db = Di::_()->get('Database\Cassandra\Cql');
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        $this->out('Missing subcommand');
    }

    public function stripe()
    {
        $this->db = Di::_()->get('Database\Cassandra\Cql'); //construct not being hit?
        $fo = fopen("/home/ubuntu/customers.csv", "r");
        $row = 0;
        while (($data = fgetcsv($fo, 10000, ",")) !== FALSE) {
            $row++;
            $id = $data[0];
            $guid = $data[29];
            try {
                $insert = new Core\Data\Cassandra\Prepared\Custom();
                $insert->query("INSERT INTO user_index_to_guid (key, column1, value) VALUES (?, ?, ?)",
                [
                  "$guid:payments",
                  "customer_id",
                  $id
                ]);
                $this->db->request($insert);
                $this->out("$guid with customer id $id done");
            } catch (\Exception $e) {
                 $this->out("$guid with customer id $id failed");
            }

        }
        $this->out($count);
    }
}
