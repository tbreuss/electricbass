<?php
/**
 * @var View $this
 * @var string $selectedCategory
 */

use app\helpers\Html;
use app\models\Glossar;
use yii\web\View;

?>

<?php $this->beginBlock('sidebar') ?>

<div class="sidebarWidget">
    <h3 class="sidebarWidget__title">E-Bass-Glossar</h3>
    <ul class="sidebarWidget__list">
    <li class="sidebarWidget__item">
        <?php $class = empty($selectedCategory) ? 'sidebarWidget__link--active' : 'sidebarWidget__link' ?>
        <?php echo Html::a('Alle Einträge', array('/glossar/index'), ['class' => $class]) ?>
    </li>
    <?php foreach(Glossar::queryAllCategories() AS $category): ?>
    <li class="sidebarWidget__item">
        <?php $class = (strtolower($category['category']) == $selectedCategory) ? 'sidebarWidget__link--active' : 'sidebarWidget__link' ?>
        <?php echo Html::a($category['category'], array('/glossar/index', 'category' => strtolower($category['category'])), ['class' => $class]); ?>
    </li>
    <?php endforeach; ?>
    </ul>
</div>

<?php $this->endBlock() ?>
