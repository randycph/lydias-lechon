<?php

namespace App\Shortcodes;

use App\EcommerceModel\Member;

class NewslettersShortcodes extends Model
{
    public function latest_homepage($shortcode, $content, $compiler, $name, $viewData)
    {

        return sprintf(view('theme.'.env('FRONTEND_TEMPLATE').'.pages.shortcodes.home-newsletter'), $shortcode->class, $content);
    }
}
