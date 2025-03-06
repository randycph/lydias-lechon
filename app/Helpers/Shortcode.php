<?php 

namespace App\Helpers;

class Shortcode
{
    /**
     * List of available shortcodes.
     */
    protected static $shortcodes = [
        'latest news'       => 'App\Http\Controllers\LatestNewsShortcode',
        'featured products' => 'App\Http\Controllers\FeaturedProductsShortcode',
        'latest homepage'   => 'App\Http\Controllers\LatestHomepageShortcode',
        'related videos'    => 'App\Http\Controllers\RelatedVideosShortcode',
    ];

    /**
     * Process shortcodes in content
     */
    public static function process($content)
    {
        return preg_replace_callback('/\[(.*?)\]/', function ($matches) {
            $fullShortcode = trim($matches[1]); 
            preg_match('/^([\w\s]+)(.*)$/', $fullShortcode, $parts);

            $shortcodeName = strtolower(trim($parts[1])); // Keep space formatting
            $attributes = isset($parts[2]) ? self::parseAttributes($parts[2]) : [];

            if (isset(self::$shortcodes[$shortcodeName])) {
                $handler = app(self::$shortcodes[$shortcodeName]);
                return $handler->render($attributes);
            }

            return $matches[0]; // Return original text if shortcode not found
        }, $content);
    }

    /**
     * Parse shortcode attributes (key="value")
     */
    protected static function parseAttributes($text)
    {
        preg_match_all('/(\w+)="([^"]*)"/', $text, $matches, PREG_SET_ORDER);
        $attributes = [];

        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[2];
        }

        return $attributes;
    }
}
