<?php

/**
 * @var app\models\Lesson[] $models
 */

use app\widgets\ListView;
use yii\widgets\Spaceless;

?>
<?php Spaceless::begin() ?>

<?= ListView::widget(["ratingStyle" => "none", "ratingContext" => "lesson", "models" => $models]) ?>

<?php Spaceless::end();
