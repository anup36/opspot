<?php

/**
 * Opspot Blogs Create Activity Delegate
 *
 * @author emi
 */

namespace Opspot\Core\Blogs\Delegates;

use Opspot\Core\Blogs\Blog;
use Opspot\Core\Entities\Actions\Save;
use Opspot\Entities\Activity;

class CreateActivity
{
    /** @var Save */
    protected $saveAction;

    /**
     * CreateActivity constructor.
     * @param null $saveAction
     */
    public function __construct($saveAction = null)
    {
        $this->saveAction = $saveAction ?: new Save();
    }

    /**
     * Creates a new activity for a blog
     * @param Blog $blog
     * @throws \Opspot\Exceptions\StopEventException
     */
    public function save(Blog $blog)
    {
        $owner = $blog->getOwnerEntity();

        $activity = (new Activity())
            ->setTitle($blog->getTitle())
            ->setBlurb(strip_tags($blog->getBody()))
            ->setURL($blog->getURL())
            ->setThumbnail($blog->getIconUrl())
            ->setFromEntity($blog)
            ->setMature($blog->isMature())
            ->setOwner($owner->export());

        $activity->container_guid = $owner->guid;
        $activity->owner_guid = $owner->guid;
        $activity->ownerObj = $owner->export();

        $this->saveAction
            ->setEntity($activity)
            ->save();
    }
}
