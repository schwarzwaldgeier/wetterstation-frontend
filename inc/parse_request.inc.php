<?php

function parse_request($container = null, $overwrite = true)
{
    $query = array();
    if (!empty($_SERVER['REQUEST_URI'])) {
        $query_string = substr($_SERVER['REQUEST_URI'],
                               strrpos($_SERVER['REQUEST_URI'], '?') + 1);
        parse_str($query_string, $query);
        if (!empty($query)) {
            // check, if we have to merge keys to container
            $merge = is_array($container);
            $keys = array_keys($query);
            foreach ($keys as $key) {
                // parse_str adds additional slashes ;-(
                if (is_string($query[$key])) {
                    $query[$key] = stripslashes($query[$key]);
                }
                // check merging for this key
                if ($merge and ($overwrite or !array_key_exists($key, $container))) {
                    $container[$key] = $query[$key];
                }
            }
        }
    }
    return $query;
}
?>
