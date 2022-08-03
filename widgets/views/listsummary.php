<?php

/**
 * @var string $summary
 * @var Sort $sort
 * @var string $sortLabel
 * @var string $sortDirection (up|down)
 */

use yii\data\Sort;
use yii\widgets\LinkSorter;

?>
<div class="widgetListSummary">
    <div class="row">
        <div class="col-6 widgetListSummary__summary"><?= $summary ?></div>
        <div class="col-6 widgetListSummary__sorter">
            <div class="dropdown">
                <a href="#" class="dropdown__button" type="button">
                     Sortiert nach<span class="caret"></span>: <?= $sortLabel ?><?= $sortDirection === 'asc' ? '&uarr;' : '&darr;' ?></span>
                </a>
                <div class="dropdown__content">
                    <?= LinkSorter::widget([
                        'sort' => $sort,
                        'options' => [
                            'class' => 'dropdown__list',
                            'itemOptions' => [
                                'class' => 'dropdown__item'
                            ],
                        ],
                        'linkOptions' => [
                                'class' => 'dropdown__link'
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
