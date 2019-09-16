<?php

/**
 * Opspot Blogs Slug
 *
 * @author emi
 */

namespace Opspot\Core\Blogs\Delegates;

use Opspot\Core\Blogs\Blog;

class Slug
{
    public function generate(Blog $blog)
    {
        if (!$blog->getPermaUrl() || !$blog->isPublished()) {
            if ($blog->getTitle() && !$blog->getSlug()) {
                $blog->setSlug($blog->getTitle());
            }
        }

        if ($blog->isDirty('slug')) {
            $blog->setPermaUrl($blog->getUrl());
        }
    }
}
