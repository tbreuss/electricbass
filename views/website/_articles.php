<?php
/**
 * @var View $this
 * @var string $title
 * @var Website[] $models
 */

use app\models\Website;
use yii\web\View;

?>
<?php if (!empty($models)): ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title"><?= $title ?></h3>
        <ul class="sidebarWidget__list">
            <?php foreach($models AS $model): ?>
                <li class="sidebarWidget__item">
                    <a class="sidebarWidget__link" href="<?= $model->url ?>">
                        <strong><?= $model->title ?></strong><br>
                        <span class="text-muted"><?= join(', ', $model->getTagsAsArray(['Bass-Link'])) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
