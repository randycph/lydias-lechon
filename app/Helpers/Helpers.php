<?php 

if(!function_exists('isImageBroken')) {
    function isImageBroken($imageUrl) {
        $imageUrl = trim($imageUrl);

        $headers = @get_headers($imageUrl, 1);

        if ($headers && strpos($headers[0], '200') !== false) {
            if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image/') === 0) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('format_price')) {
    function format_price($price)
    {
        return '₱' . number_format($price, 2);
    }
}

if (!function_exists('isDispatcher')) {
    function isDispatcher()
    {
        return auth()->user()->role_id == 5;
    }
}

if (!function_exists('isForecaster')) {
    function isForecaster()
    {
        return auth()->user()->role_id == 3;
    }
}

if (!function_exists('highlightPaella')) {
    function highlightPaella($name)
    {
        if (empty($name)) {
            return $name;
        }
        $highlight = 'Boneless with Paella';
        return str_replace($highlight, "<b>{$highlight}</b>", $name);
    }
}



