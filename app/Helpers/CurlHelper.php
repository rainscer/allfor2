<?php

    if (!function_exists('curl_reset')) {

        function curl_reset($ch)
        {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_POST, false);
        }
    }