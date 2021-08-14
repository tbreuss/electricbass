<?php

namespace app\widgets;

class LinkPager extends \yii\widgets\LinkPager
{
    public $maxButtonCount = 5;
    public $options = ['class' => 'pagination'];
    public $linkContainerOptions = ['class' => 'pagination__item'];
    public $linkOptions = ['class' => 'pagination__link'];
    public $firstPageCssClass = 'pagination__item--first';
    public $lastPageCssClass = 'pagination__item--last';
    public $prevPageCssClass = 'pagination__item--prev';
    public $nextPageCssClass = 'pagination__item--next';
    public $activePageCssClass = 'is-active';
    public $disabledPageCssClass = 'is-disabled';
    public $disabledListItemSubTagOptions = ['class' => 'pagination__link'];
    //public $firstPageLabel = 'Erste Seite';
    //public $lastPageLabel = 'Letzte Seite';
}
