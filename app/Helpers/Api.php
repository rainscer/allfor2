<?php

if (!function_exists('remote')) {

    function remote($url, $postData = [])
    {
        $decoded = new stdclass;

        if ($url) {
            $service_url = $url;
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, (array)$postData);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            $decoded = json_decode($curl_response);
        }

        if (!$url || json_last_error() !== JSON_ERROR_NONE) {
            $decoded = new stdclass;
            $decoded->status = 'ERROR';
        }

        return $decoded;
    }

}

if (!function_exists('isValidResponse')) {

    function isValidResponse($response)
    {
        return $response->status == 'ERROR' ? false : true;
    }
}