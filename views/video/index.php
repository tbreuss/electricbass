<?php

/**
 * @var yii\web\View $this
 * @var yii\data\Pagination $pagination
 * @var string $sort
 * @var app\models\Video[] $videos
 */

use app\widgets\ListSummary;
use app\widgets\LinkPager;

$this->title = 'Videos';
$this->params['breadcrumbs'][] = 'Videos';
$this->params['pageTitle'] = sprintf('Videos von und für E-Bassist*innen (%d/%d)', $pagination->page+1, $pagination->pageCount);
$this->params['metaDescription'] = sprintf('Videos von und für E-Bassisten und Bassistinnen. Lass dich von anderen Bassisten inspirieren und lerne von den bessten ihres Fachs. (Seite %d von %d)', $pagination->page+1, $pagination->pageCount);

?>

<div class="content">

    <h1>Videos von und für E-Bassisten</h1>

    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <div class="row videoList">
        <?php foreach ($videos as $i => $video): ?>
            <div class="col-6 col-xl-4 videoList__cell">
                <a href="<?= $video->url ?>" class="videoList__link">
                    <?php if ($video->platform === 'youtube'): ?>
                        <img class="img-fluid videoList__cover" src="https://img.youtube.com/vi/<?php echo $video->key ?>/mqdefault.jpg" alt="<?php echo $video->title ?>">
                    <?php endif; ?>
                    <div class="videoList__title"><?= $video->title ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
