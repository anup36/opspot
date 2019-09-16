<?php
/**
 * Sitemap
 */
namespace Opspot\Controllers;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;

class sitemap extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        header('Content-type: application/xml');
        echo <<< XML
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
      <url>
          <loc>https://www.ops.doesntexist.com/</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/newsfeed</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/opspot</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/plus</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/discovery/trending/channels</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/discovery/trending/channels</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/blog/trending</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.ops.doesntexist.com/wire</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
  </urlset>        

XML;
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
