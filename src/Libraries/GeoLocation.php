<?php

namespace App\Libraries;

class GeoLocation
{

    /**
     * @param $ipAddress
     * @param string $geo_api_url
     * @return mixed
     */
    public function byIp($ipAddress, string $geo_api_url, $geo_api_key) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$geo_api_url?apiKey=$geo_api_key&ip=$ipAddress");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        return json_decode($response, false);

    }
}