<?php

namespace App\Vendor\Blade;
class Blade
{
    public static function getInstance()
    {
        static $blade = null;
        if ($blade == null) {
            $blade = new \Jenssegers\Blade\Blade(ROOT . '/Resource/views/', ROOT . '/Temp/TplCache');
        }
        return $blade;
    }
}