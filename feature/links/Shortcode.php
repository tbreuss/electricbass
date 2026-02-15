<?php

namespace app\feature\links;

class Shortcode
{
    public static function render(): string
    {
        return LinksWidget::widget();
    }
}
