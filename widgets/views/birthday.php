<?php
/**
 * @var app\models\Birthday $models
 */
?>
<div class="sidebarWidget">
    <h3 class="sidebarWidget__title">
        Geboren am <?php echo Yii::$app->formatter->asDate(time(), 'd. MMMM') ?>
    </h3>
    <ul class="sidebarWidget__list">
        <li class="sidebarWidget__item">
            <?php foreach($models AS $m): ?>
                <?php echo $m->name ?> (<?php echo $m->birth_year ?>)<br>
            <?php endforeach; ?>
        </li>
    </ul>
</div>
