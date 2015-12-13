<?php
/**
 * functions.php
 *
 * Created by Chongyi
 * Date & Time 2015/12/13 20:37
 */

if (!function_exists('get_uri_path')) {

    function get_uri_path($origin)
    {
        $url = parse_url($origin);

        $result = $url['path'];

        if (isset($url['query'])) {
            $result .= '?' . $url['query'];
        }

        if (isset($url['fragment'])) {
            $result .= '#' . $url['fragment'];
        }

        return $result;
    }

}