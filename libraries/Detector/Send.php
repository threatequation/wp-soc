<?php
namespace SOCLITE\Detector;

class Send
{
    private static $url = 'https://www.threatequation.com/api/v1';

    /**
     * Send a POST requst using cURL.
     *
     * @param array $post values to send
     *
     * @return string
     */
    public static function curl($endpoint, array $post = null)
    {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => array('Content-Type: application/json'),
            CURLOPT_URL => self::$url.$endpoint,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 18,
            CURLOPT_POSTFIELDS => http_build_query($post),
        );

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);
        curl_close($ch);
        return array($body, $header);
    }
}
