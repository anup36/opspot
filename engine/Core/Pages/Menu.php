<?php
/**
 * Opspot Page Menu Container
 */
namespace Opspot\Core\Pages;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Navigation;

class Menu
{
    private static $_;
    private $containers = [];

    public function init()
    {
        $footer = Di::_()->get('PagesManager')->getMenu('footer');

        usort($footer, function ($a, $b) {
            return strcmp($b->getTitle(), $a->getTitle());
        });

        foreach ($footer as $page) {
            $front_path = 'p/';
            $listed = true;

            if ($page->getSubtype() == 'link') {
                $front_path = '';
                $listed = false;
            }

            Navigation\Manager::add(
                (new Navigation\Item())
                    ->setName($page->getTitle())
                    ->setTitle($page->getTitle())
                    ->setPath("{$front_path}{$page->getPath()}")
                    ->setExtras([
                        'listed' => $listed
                    ]),
                "footer"
            );
        }
    }

    public static function _()
    {
        if (!self::$_) {
            self::$_ = new Menu();
        }
        return self::$_;
    }
}
