<?php
/**
 * Opspot Wire Rewards
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Opspot\Controllers\api\v1\wire;

use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Payments;
use Opspot\Entities;
use Opspot\Entities\User;

class rewards implements Interfaces\Api
{
    /**
     * GET
     */
    public function get($pages)
    {
        if ($pages[1] === 'entity') {
            $entity = Entities\Factory::build($pages[0]);

            if ($entity) {
                $user = $entity->type == 'user' ? $entity : $entity->getOwnerEntity();
            }
        } else {
            $user = $pages[0] ? new User($pages[0]) : Core\Session::getLoggedinUser();
        }

        if (!$user || !$user->guid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Unknown user'
            ]);
        }

        $response = [];

        $response['username'] = $user->username;

        $response['wire_rewards'] = $user->getWireRewards() ?: [];

        if (is_string($response['wire_rewards'])) {
            $response['wire_rewards'] = json_decode($response['wire_rewards'], true);
        }

        $response['wire_rewards'] = array_replace_recursive([
            'description' => '',
            'rewards' => [
                'tokens' => []
            ]
        ], $response['wire_rewards']);

        $response['merchant'] = $user->getMerchant() ?: false;
        $response['eth_wallet'] = $user->getEthWallet() ?: '';

        // Sums
        /** @var Sums $sums */
        $sums = Core\Di\Di::_()->get('Wire\Sums');
        $sums->setFrom((new \DateTime('midnight'))->modify("-30 days")->getTimestamp())
            ->setReceiver($user)
            ->setSender(Core\Session::getLoggedInUser());

        $response['sums'] = [ 
            'tokens' => $sums->getSent()
        ];

        return Factory::response($response);
    }

    /**
     * POST
     */
    public function post($pages)
    {
        $owner = Core\Session::getLoggedinUser();
        $rewards = $_POST['rewards'] ?: [];

        if ($rewards) {
            if (
                !isset($rewards['rewards']['tokens']) ||
                !is_array($rewards['rewards']['tokens'])
            ) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Missing required fields'
                ]);
            }
        }

        $update = [];
        $response = [];

        $owner->setWireRewards($rewards ?: []);
        $update['wire_rewards'] = json_encode($rewards);

        $db = new Core\Data\Call('entities');
        $db->insert($owner->guid, $update);
        
        $response['channel'] = $owner->export();

        return Factory::response($response);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
