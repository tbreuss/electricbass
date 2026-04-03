<?php

/**
 * @var yii\web\View $this
 * @var string $selectedCategory
 */

use app\features\glossar\models\Glossar;
use app\helpers\Html;

?>

<?php $this->beginBlock('sidebar') ?>

<div class="sidebarWidget">
    <h3 class="sidebarWidget__title">E-Bass-Glossar</h3>
    <ul class="sidebarWidget__list">
    <li class="sidebarWidget__item">
        <?php $class = empty($selectedCategory) ? 'sidebarWidget__link--active' : 'sidebarWidget__link' ?>
        <?php echo Html::a('Alle Einträge', ['/glossar/index'], ['class' => $class]) ?>
    </li>
    <?php $categories = Glossar::queryAllCategories() ?>
    <?php foreach ($categories as $category): ?>
    <li class="sidebarWidget__item">
        <?php $class = ($category['urlSegment'] == $selectedCategory) ? 'sidebarWidget__link--active' : 'sidebarWidget__link' ?>
        <?php echo Html::a($category['category'], ['/glossar/index', 'category' => $category['urlSegment']], ['class' => $class]); ?>
    </li>
    <?php endforeach; ?>
    </ul>
</div>

<?php $this->endBlock() ?>
