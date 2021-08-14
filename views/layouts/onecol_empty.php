<?php
    use app\widgets\Advertisement;
    use app\widgets\Articles;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <div class="row">
        <div class="col-md-8 content-wrap">
            <?= $content ?>
            <?= ArrayHelper::getValue($this->blocks, 'comments') ?>
            <div class="top">
                <a href="#" class="top__link">&#x25B2; nach oben</a>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>