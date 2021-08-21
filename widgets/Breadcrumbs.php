<?php

namespace app\widgets;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /** @var array<string, string> */
    public $options = ['class' => 'breadcrumbs'];
    public $itemTemplate = "<li class='breadcrumbs__item'>{link}</li>\n";
    public $activeItemTemplate = "<li class=\"breadcrumbs__item breadcrumbs__item--active\">{link}</li>\n";
}
