<?php

namespace app\features\links;

class Shortcode
{
    public static function render(): string
    {
        return LinksWidget::widget();
    }
}
