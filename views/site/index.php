<?php

/**
 * @var yii\web\View $this
 * @var app\models\Search[] $models
 * @var int $count
 * @var app\models\Comment[] $latestComments
 * @var app\models\Rating[] $latestRatings
 */

use app\helpers\Html;
use app\widgets\Advertisement;
use app\widgets\Birthday;
use app\widgets\CanonicalLink;
use app\widgets\Joke;
use app\widgets\Quote;
use app\widgets\RatingReadOnly;
use yii\helpers\Markdown;

$this->title = 'ELECTRICBASS - Alles über das bässte Instrument der Welt';
$this->params['pageTitle'] = 'ELECTRICBASS - Alles über das bässte Instrument der Welt';
$this->params['metaDescription'] = 'Umfangreiche Informationen zum E-Bass mit Lektionen, Musikalben, Lehrbüchern, Lehrvideos, Videos, Websites und mehr. Lass dich zum Bass spielen inspirieren!';
CanonicalLink::widget();
?>

<div class="content">

    <h1>Die neuesten Einträge</h1>

    <?php if (!empty($latestVideos)): ?>
        <div class="row videoList">
            <div class="col-12"><h2><?= $latestVideos[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestVideos as $latest): ?>
            <div class="col-6 col-xl-4 videoList__cell">
                <a href="<?= $latest->url ?>" class="videoList__link">
                    <?= Html::img($latest->getDefaultImage(), ["class" => "img-fluid videoList__cover", "alt" => $latest->title]) ?>
                    <div class="videoList__title"><?= $latest->title ?></div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($latestBlogs)): ?>
        <div class="row">
            <div class="col-12"><h2><?= $latestBlogs[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestBlogs as $latest): ?>
                <div class="col-12">
                    <h3><a href="<?= $latest->url ?>"><?= $latest->title ?></a></h3>
                    <?= RatingReadOnly::widget(["style" => "none", "tableName" => $latest->tableName, "tableId" => $latest->tableId, "ratingCount" => $latest->ratings, "ratingAverage" => $latest->ratingAvg]) ?>
                    <p><?= strip_tags(Markdown::process($latest->abstract)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($latestAlbums)): ?>
        <div class="row albumlist">
            <div class="col-12"><h2><?= $latestAlbums[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestAlbums as $latest): ?>
                <div class="col-6 col-xl-4 albumlist__cell">
                    <a href="<?= $latest->url ?>" class="albumlist__link">
                        <?php if ($latest->getDefaultImage()): ?>
                            <?= Html::img($latest->getDefaultImage('@web'), ["class" => "img-fluid albumlist__cover", "alt" => $latest->title]) ?>
                        <?php else: ?>
                            <?= Html::img('/img/bg.png', ["width" => 290, "height" => 290, "class" => "img-fluid albumlist__cover", "alt" => ""]) ?>
                        <?php endif; ?>
                        <div class="albumlist__title"><?= $latest->title ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($latestLessons)): ?>
        <div class="row">
            <div class="col-12"><h2><?= $latestLessons[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestLessons as $latest): ?>
                <div class="col-12">
                    <h3><a href="<?= $latest->url ?>"><?= $latest->title ?></a></h3>
                    <?= RatingReadOnly::widget(["style" => "none", "tableName" => $latest->tableName, "tableId" => $latest->tableId, "ratingCount" => $latest->ratings, "ratingAverage" => $latest->ratingAvg]) ?>
                    <p><?= strip_tags(Markdown::process($latest->abstract)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php $even = date('z') % 2 === 0; ?>

    <?php if (!$even && !empty($latestLehrbuecher)): ?>
        <div class="row cataloglist">
            <div class="col-12"><h2><?= $latestLehrbuecher[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestLehrbuecher as $model): ?>
                <?php $relImage = $model->getDefaultImage() ?>
                <?php $emptyClass = !empty($relImage) ? '' : 'cataloglist__link--empty' ?>
            <div class="col-6 col-sm-3 col-md-6 col-lg-3 cataloglist__cell">
                <a href="<?= $model->url ?>" class="cataloglist__link <?= $emptyClass ?>">
                    <?php if (!empty($relImage)): ?>
                        <?= Html::resizeImage($relImage, 290, 580, ["class" => "img-fluid cataloglist__cover", "alt" => $model->title]) ?>
                    <?php else: ?>
                        <span class="img-fluid cataloglist__cover"></span>
                    <?php endif; ?>
                    <div class="cataloglist__title"><?= $model->title ?></div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <?php if ($even && !empty($latestBuecher)): ?>
        <div class="row cataloglist">
            <div class="col-12"><h2><?= $latestBuecher[0]->getContextTextPlural() ?></h2></div>
            <?php foreach ($latestBuecher as $model): ?>
                <?php $relImage = $model->getDefaultImage() ?>
                <?php $emptyClass = !empty($relImage) ? '' : 'cataloglist__link--empty' ?>
                <div class="col-6 col-sm-3 col-md-6 col-lg-3 cataloglist__cell">
                    <a href="<?= $model->url ?>" class="cataloglist__link <?= $emptyClass ?>">
                        <?php if (!empty($relImage)): ?>
                            <?= Html::resizeImage($relImage, 290, 580, ["class" => "img-fluid cataloglist__cover", "alt" => $model->title]) ?>
                        <?php else: ?>
                            <span class="img-fluid cataloglist__cover"></span>
                        <?php endif; ?>
                        <div class="cataloglist__title"><?= $model->title ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-12">

            <div class="last">
                <h2 class="last__title">Zuletzt kommentiert</h2>
                <?php foreach ($latestComments as $latestComment): ?>
                    <?php $search = $latestComment->search ?>
                    <p class="last__item">
                        <a class="last__link" href="<?= $search->url ?>#comments"><b><?= $search->title ?></b></a>
                        <span class="last__text">Von <?= $latestComment->name ?> am <?= Yii::$app->formatter->asDate($latestComment->created, 'long') ?></span>
                    </p>
                <?php endforeach; ?>
            </div>
            <hr>
            <div class="last">
            <h2 class="last__title">Zuletzt bewertet</h2>
            <?php foreach ($latestRatings as $latestRating): ?>
                <?php $search = $latestRating->search ?>
                <?php if ($search): ?>
                <p class="last__item">
                    <a class="last__link" href="<?= $search->url ?>"><b><?= $search->title ?></b></a>
                    <span class="last__text">Wert <?= number_format($latestRating->value, 1) ?> am <?= Yii::$app->formatter->asDate($latestRating->modified, 'long') ?></span>
                </p>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<?php $this->beginBlock('jumbotron') ?>
<div class="jumbotron">
    <div class="jumbotron__wrapper container">
        <div class="jumbotron__heading">Hallo Bassist*in</div>
        <div class="jumbotron__text">Auf diesem Blog findest du mehr als <strong><?= $count ?> Artikel</strong> über das bässte Instrument der Welt. Viel Spass beim Surfen 🤘</div>
        <a class="jumbotron__copy" target="_blank" href="https://www.pexels.com/photo/singer-singing-on-stage-beside-guitar-player-and-bass-player-167636/">Photo by Thibault Trillet from Pexels</a>
    </div>
</div>
<style>
:root {
    --jumbotron_heading_size: 1.75rem;
    --jumbotron_text_size: 1.25rem;
}
@media (min-width: 576px) {
    :root {
        --jumbotron_heading_size: 4rem;
        --jumbotron_text_size: 1.5rem;
    }
}
.jumbotron {
    background-image: url(/img/pexels-thibault-trillet-167636.jpg);
    background-position: 0px 19rem;
    background-size: cover;
    background-color: #01020a;
    margin-top: -1px;
}
.jumbotron__wrapper {
    position:relative;
    height: 19rem;
}
.jumbotron__heading {
    font-weight: 600;
    font-size: var(--jumbotron_heading_size);
    color: rgba(255,255,255,1);
    padding-top: 3rem;
    text-align: center;
    text-shadow: 3px 3px 9px #000;
}
.jumbotron__text {
    font-weight: 600;
    font-size: var(--jumbotron_text_size);
    text-shadow: 2px 2px 4px #000;
    color: rgba(255,255,255,1);
    text-align: center;
    margin:0 auto;
    max-width: 80%;
}
.jumbotron__copy {
    position:absolute;
    bottom: 0.25rem;
    right: 0.5rem;
    font-size: 0.6rem;
    color: rgba(255, 255, 255, 0.8)
}
.jumbotron__copy:hover {
    color: rgba(255, 255, 255, 0.9)
}
</style>
<?php $this->endBlock() ?>

<?php $this->beginBlock('sidebar') ?>
<?= Advertisement::widget() ?>

<div style="margin-bottom: 1rem">
    <?= app\widgets\Banner::widget() ?>
</div>

<?= Birthday::widget() ?>
<?= Quote::widget() ?>
<?= Joke::widget() ?>
<?php $this->endBlock() ?>
