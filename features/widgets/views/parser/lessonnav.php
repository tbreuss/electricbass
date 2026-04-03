<?php

/**
 * @var app\features\lesson\models\Lesson[] $models
 */

use app\features\widgets\ListView;
use yii\widgets\Spaceless;

?>
<?php Spaceless::begin() ?>

<?= ListView::widget(["ratingStyle" => "none", "ratingContext" => "lesson", "models" => $models]) ?>

<?php Spaceless::end();
