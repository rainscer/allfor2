<?php
use Illuminate\Support\Facades\Request;

if (!function_exists('classActivePath')) {
    function classActivePath($path)
    {
        $active = '';

        if (Request::is(Request::segment(1) . '/' . $path . '/*') ||
            Request::is(Request::segment(1) . '/' . $path) ||
            Request::is($path) ||
            Request::is($path . '/*'))
        {
            $active = 'active';
        }

        return $active;
    }
}