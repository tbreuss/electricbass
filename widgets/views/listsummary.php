<?php
use yii\widgets\LinkSorter;

?>
<div class="widgetListSummary">
    <div class="row">
        <div class="col-7 widgetListSummary__summary"><?= $summary ?></div>
        <div class="col-5 widgetListSummary__sorter">
            <div class="dropdown">
                <a href="#" class="dropdown__button" type="button">
                     Sortieren nach </span><span class="caret"></span>
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
