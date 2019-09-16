<?php

namespace Opspot\Controllers\api\v2\onboarding;

use Opspot\Api\Factory;
use Opspot\Core\Onboarding\Manager;
use Opspot\Core\Session;
use Opspot\Interfaces;

class progress implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        /** @var Manager */
        $manager = new Manager();
        $manager->setUser(Session::getLoggedInUser());

        $allItems = $manager->getAllItems();
        $completedItems = $manager->getCompletedItems();

        return Factory::response([
            'show_onboarding' => !$manager->wasOnboardingShown() && count($allItems) > count($completedItems) ,
            'all_items' => $allItems,
            'completed_items' => $completedItems,
            'creator_frequency' => $manager->getCreatorFrequency(),
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
