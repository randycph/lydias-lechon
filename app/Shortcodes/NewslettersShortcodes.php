<?php

namespace App\Shortcodes;

use App\EcommerceModel\Member;

class NewslettersShortcodes extends Model
{
    public function latest_homepage($shortcode, $content, $compiler, $name, $viewData)
    {

        return sprintf(view('theme.'.config('app.frontend_template').'.pages.shortcodes.home-newsletter'), $shortcode->class, $content);
    }
}
