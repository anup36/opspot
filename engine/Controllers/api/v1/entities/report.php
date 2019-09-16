<?php
/**
 * Opspot Entity Report API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Opspot\Controllers\api\v1\entities;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class report implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (count($pages) < 1) {
            return Factory::response([]);
        }

        $reason = isset($_POST['subject']) && $_POST['subject'] ? $_POST['subject'] : null;
        $reason_note = isset($_POST['note']) && $_POST['note'] ? $_POST['note'] : null;

        if (!$pages[0] || !$reason) {
            return Factory::response([]);
        }

        /** @var Core\Reports\Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        $done = $repository->create($pages[0], Core\Session::getLoggedinUser(),
            $reason, $reason_note);

        return Factory::response([
            'done' => $done
        ]);
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
