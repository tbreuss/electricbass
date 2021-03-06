<?php

/**
 * @var yii\web\View $this
 * @var app\models\Video $video
 * @var app\models\Video[] $similarVideos
 * @var int $height
 * @var string $key
 * @var int $width
 */

use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Parser;
use app\widgets\Rating;
use app\widgets\SocialBar;
use yii\helpers\Url;

$this->title = $video->title . ' | Videos';
$this->params['breadcrumbs'][] = [
    'label' => 'Videos',
    'url' => Url::previous('video')
];
$this->params['breadcrumbs'][] = $video->title;
$topVideos = array_slice($similarVideos, 0, 3);
$moreVideos = array_slice($similarVideos, 3);
?>

<div class="row">
    <div class="col-md-12">
        <div class="content">
            <div class="row">
                <div class="col-md-8">
                    <h1><?= $video->title ?></h1>
                </div>
                <div class="col-md-8">
                    <?php if ($video->platform === 'youtube'): ?>
                        <div class="ratio ratio--16x9">
                            <iframe src="https://www.youtube.com/embed/<?php echo $video->key ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    <?php elseif ($video->platform === 'vimeo'): ?>
                        <div class="ratio ratio--16x9">
                            <iframe src="https://player.vimeo.com/video/<?php echo $key ?>?color=ffffff" width="<?php echo $width ?>" height="<?php echo $height ?>" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($video->text)): ?>
                    <div class="widget widget-parser" style="margin-top: 1rem">
                        <?= Parser::widget(["model" => $video, "attribute" => "text"]) ?>
                    </div>
                    <?php endif; ?>

                    <?= $this->render('//_partials/meta', [
                        'categories' => [
                            ['label' => 'Katalog', 'url' => ['/catalog/overview']],
                            ['label' => 'Bass-Videos', 'url' => Url::previous("video")]
                        ],
                        'tags' => $video->tags,
                    ]); ?>

                    <?= Rating::widget(["tableName" => "video", "tableId" => $video->id]) ?>

                    <?= SocialBar::widget(["id" => $video->id, "text" => $video->title]) ?>

                    <div class="moreVideos">
                        <h2>Diese Videos k??nnten dir auch gefallen</h2>
                        <div class="row">
                            <?php foreach ($moreVideos as $i => $moreVideo): ?>
                            <div class="col-6 col-xl-4 moreVideos__row">
                                <div class="moreVideos__video">
                                    <a href="<?= $moreVideo->url ?>">
                                        <?php if ($moreVideo->platform === 'youtube'): ?>
                                            <img class="img-fluid" src="https://img.youtube.com/vi/<?php echo $moreVideo->key ?>/mqdefault.jpg" alt="<?php echo $moreVideo->title ?>">
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="moreVideos__link">
                                    <a href="<?= $moreVideo->url ?>"><?= $moreVideo->title ?></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
                <div class="d-none d-md-block col-4">
                    <div class="topVideos">
                    <h2 class="topVideos__heading">??hnliche Videos</h2>
                        <?php foreach ($topVideos as $topVideo): ?>
                            <a class="topVideos__item" href="<?= $topVideo->url ?>">
                                <?php if ($topVideo->platform === 'youtube'): ?>
                                    <img class="topVideos__image" src="https://img.youtube.com/vi/<?php echo $topVideo->key ?>/mqdefault.jpg" alt="<?php echo $topVideo->title ?>">
                                    <span class="topVideos__label"><?= $topVideo->title ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <?= Comments::widget(["tableName" => "video", "tableId" => $video->id]) ?>
                    <?= Hits::widget(["tableName" => "video", "tableId" => $video->id]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .moreVideos {
        margin-bottom: 1rem;
    }
    .moreVideos__row {
        margin-bottom: 1rem;
    }
    .moreVideos__link {
        white-space: nowrap;
        overflow: hidden;text-overflow: ellipsis;
        font-size: 0.8rem;
    }
    .moreVideos__link a {
        color: #5F5C52;
    }
    .topVideos__item {
        display: block;
        color: #5F5C52;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }
    .topVideos__item:hover {
        color: #5F5C52;
    }
    .topVideos__image {
        display: block;
        max-width: 100%;
        height: auto;
    }
</style>
