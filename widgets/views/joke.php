<?php

    use app\helpers\Html;

?>
<?php if (empty($model)) {
    return;
} ?>

<div class="sidebarWidget sidebarWidget--joke">
    <h3 class="sidebarWidget__title"><?= Html::a('Bassistenwitze', ['joke/index']) ?></h3>
    <div><?= nl2br($model->joke) ?></div>
</div>
