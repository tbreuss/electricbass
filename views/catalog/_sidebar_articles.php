<?php
/**
 * @var app\models\Catalog[] $models
 * @var string $title
 */
?>
<?php if (!empty($models)): ?>
    <div class="sidebarWidget sidebarWidget--simple">
        <h3 class="sidebarWidget__title"><?= $title ?></h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($models as $model): ?>
                <li class="sidebarWidget__item">
                    <a class="sidebarWidget__link" href="<?= $model->url ?>"><?= $model->title ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
