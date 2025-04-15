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
