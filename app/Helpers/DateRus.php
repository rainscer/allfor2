<?php

if(!function_exists('date_rus')) {
    function date_rus($date, $format = 'j MMM Y') {

        return \App\Models\Date::parse($date)->format();
    }
}