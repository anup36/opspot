<?php
/**
 * @author Mark
 */

namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Queue;
use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Di\Di;

class BlockchainTransactions implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("BlockchainTransactions")
            ->receive(function ($msg) {

                $data = $msg->getData();

                echo "Received a new blockchain transaction. Tx: {$data['tx']} User: {$data['user_guid']}\n";

                $manager = Di::_()->get('Blockchain\Transactions\Manager');
                $manager
                    ->setUserGuid($data['user_guid'])
                    ->setTimestamp($data['timestamp'])
                    ->setWalletAddress($data['wallet_address'])
                    ->setTx($data['tx'])
                    ->run();
            });
    }

}
