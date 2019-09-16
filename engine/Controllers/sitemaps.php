<?php


namespace Opspot\Controllers;

use Opspot;
use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\SEO\Sitemaps\Modules\SitemapFeatured;
use Opspot\Core\SEO\Sitemaps\Modules\SitemapRouter;
use Opspot\Core\SEO\Sitemaps\Modules\SitemapTrending;
use Opspot\Interfaces;

class sitemaps implements Interfaces\Api
{
    public function get($pages)
    {
        $sitemap = new Core\SEO\Sitemaps\Manager();
        $sitemap->addModules([
            'master' => SitemapRouter::class,
            'discovery/trending' => SitemapTrending::class,
            'blogs/trending' => SitemapTrending::class,
            'groups/trending' => SitemapTrending::class,

            'discovery/featured' => SitemapFeatured::class,
            'blogs/featured' => SitemapFeatured::class,
            'groups/featured' => SitemapFeatured::class,
        ]);

        header('Content-type: application/xml');
        echo $sitemap->getSitemap(implode('/',$pages));
    }

    public function post($pages)
    {
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
