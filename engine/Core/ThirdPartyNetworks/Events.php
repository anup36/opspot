<?php
namespace Opspot\Core\ThirdPartyNetworks;

use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Events\Event;

class Events
{
    /**
     * Register Third-Party Networks events
     * @return void
     */
    public function register()
    {
        Dispatcher::register('social', 'dispatch', function (Event $event) {
            $params = $event->getParameters();
            $entity = $params['entity'];
            $services = $params['services'] ?: [];

            if (!$entity) {
                return;
            }

            $results = [];

            foreach ($services as $service => $enabled) {
                $results[$service] = false;

                if ($enabled) {
                    try {
                        $handler = Factory::build($service);

                        $handler->getApiCredentials();
                        $results[$service] = $handler->post($entity);
                    } catch (\Exception $e) {
                        // Let's just fail silently
                        error_log('[social.dispatch Exception] ' . $e->getMessage());
                    }
                }
            }

            $event->setResponse($results);
        });
    }
}
