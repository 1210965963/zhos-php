<?php


namespace zhos\http;


use http\Exception\RuntimeException;
use zhos\Zos;

class HttpClient
{
    public static function post(string $url, array $post = [], array $options = []) : string
    {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 60,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );

        $starttime = time();
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            throw new \RuntimeException(curl_error($ch));
        }
        curl_close($ch);
        $requestTime = time() - $starttime;
        if ($requestTime > 1) {
            Zos::getLog()->info($url . ' ' . json_encode($post) . ' ' . $requestTime . 's');
        }

        return $result;
    }

    public static function get(string $url, array $get = [], array $options = []) : string
    {
        $defaults = array(
            CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 60,
            CURLOPT_TIMEOUT => 5
        );

        $starttime = time();
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            throw new \RuntimeException(curl_error($ch));
        }
        curl_close($ch);

        $requestTime = time() - $starttime;
        if ($requestTime > 1) {
            Zos::getLog()->info($url . ' ' . json_encode($get) . ' ' . $requestTime . 's');
        }

        return $result;
    }
}