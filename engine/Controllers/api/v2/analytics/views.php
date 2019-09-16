<?php


namespace Opspot\Controllers\api\v2\analytics;


use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Entities;
use Opspot\Helpers\Counters;
use Opspot\Interfaces;


class views implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        switch ($pages[0]) {
            case 'boost':
                $expire = Di::_()->get('Boost\Network\Expire');
                $metrics = Di::_()->get('Boost\Network\Metrics');

                $boost = Core\Boost\Factory::build("Newsfeed")->getBoostEntity($pages[1]);
                if (!$boost) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Could not find boost'
                    ]);
                }

                $count = $metrics->incrementViews($boost);

                if ($count > $boost->getImpressions()) {
                    $expire->setBoost($boost);
                    $expire->expire();
                }

                Counters::increment($boost->getEntity()->guid, "impression");
                Counters::increment($boost->getEntity()->owner_guid, "impression");
                break;
            case 'activity':
                $activity = new Entities\Activity($pages[1]);

                if (!$activity->guid) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Could not find activity post'
                    ]);
                }

                try {
                    Core\Analytics\App::_()
                        ->setMetric('impression')
                        ->setKey($activity->guid)
                        ->increment();

                    if ($activity->remind_object) {
                        Core\Analytics\App::_()
                            ->setMetric('impression')
                            ->setKey($activity->remind_object['guid'])
                            ->increment();

                        Core\Analytics\App::_()
                            ->setMetric('impression')
                            ->setKey($activity->remind_object['owner_guid'])
                            ->increment();
                    }

                    Core\Analytics\User::_()
                        ->setMetric('impression')
                        ->setKey($activity->owner_guid)
                        ->increment();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
                break;
        }

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
