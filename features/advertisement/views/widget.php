<?php

/**
 * @var string $title
 */

use app\helpers\Html;
use app\helpers\Url;

?>
<?php if (!empty($rows)): ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title"><?= Html::a($title, ['advertisement/index']) ?></h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($rows as $row): ?>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= Url::to($row['url']) ?>">
                    <strong><?= $row['title'] ?></strong><br>
                    <?= Yii::$app->formatter->asDate($row['date'], 'long') ?> / <?php echo $row['region'] ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
