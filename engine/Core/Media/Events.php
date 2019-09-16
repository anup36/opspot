<?php
namespace Opspot\Core\Media;

use Opspot\Core;
use Opspot\Core\Events\Dispatcher;
use Opspot\Entities;

class Events
{
    public function register()
    {
        Dispatcher::register('entities:map', 'all', function ($event) {
            $params = $event->getParameters();

            if ($params['row']->type == 'object') {
                switch ($params['row']->subtype) {
                    case 'video':
                    case 'kaltura_video':
                        $event->setResponse(new Entities\Video($params['row']));
                        break;
                    
                    case 'audio':
                        $event->setResponse(new Entities\Audio($params['row']));
                        break;
                    
                    case 'image':
                        $event->setResponse(new Entities\Image($params['row']));
                        break;

                    case 'album':
                        $event->setResponse(new Entities\Album($params['row']));
                        break;
                }
            }
        });
    }
}
