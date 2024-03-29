<?php
/**
 * Entities
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Opspot\Core\Feeds\Top;


use Opspot\Core\Blogs\Blog;
use Opspot\Core\Di\Di;
use Opspot\Core\EntitiesBuilder;
use Opspot\Entities\Activity;
use Opspot\Entities\Image;
use Opspot\Entities\Video;

class Entities
{
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    public function __construct($entitiesBuilder = null)
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param mixed $entity
     * @return bool
     */
    public function filter($entity)
    {
        return $entity->getAccessId() != 0;
    }

    /**
     * @param mixed $entity
     * @return Activity
     * @throws \Exception
     */
    public function cast($entity)
    {
        if (is_string($entity) || is_numeric($entity)) {
            $entity = $this->entitiesBuilder->single($entity);
        } elseif (is_array($entity)) {
            $entity = $this->entitiesBuilder->build($entity);
        }

        if ($entity instanceof Activity) {
            return $entity;
        }

        $fields = [
            'guid',
            'time_created',
            'owner_guid',
            'container_guid',
            'access_id',
            'time_updated',
            'mature',
            'spam',
            'deleted',
            'paywall',
            'edited',
            'comments_enabled',
            'wire_threshold',
            'rating',
            'impressions',
            'thumbs:up:user_guids',
            'thumbs:up:count',
            'thumbs:down:user_guids',
            'thumbs:down:count',
        ];

        $activity = new Activity();
        $activity->setEphemeral(true);

        if ($entity instanceof Blog) {
            // New entities
            $fromExport = $entity->export();

            foreach ($fields as $field) {
                if (isset($fromExport[$field])) {
                    $activity->set($field, $fromExport[$field]);
                }
            }
        } else {
            // Legacy entity getter
            foreach ($fields as $field) {
                $activity->set($field, $entity->{$field});
            }
        }

        if ($entity instanceof Image || $entity instanceof Video) {
            // Images, Videos
            $activity
                ->setFromEntity($entity)
                ->setTitle($entity->title)
                ->setBlurb($entity->description)
                ->setMature($entity->getFlag('mature'))
                ->setCustom(...$entity->getActivityParameters());
        } elseif ($entity instanceof Blog) {
            $activity
                ->setFromEntity($entity)
                ->setTitle($entity->getTitle())
                ->setBlurb(strip_tags($entity->getBody()))
                ->setURL($entity->getURL())
                ->setThumbnail($entity->getIconUrl())
                ->setMature($entity->isMature());
        }

        return $activity;
    }
}
