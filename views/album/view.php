<?php

/**
 * @var app\models\Album $model
 * @var yii\web\View $this
 */

use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;
use app\helpers\Html;
use app\helpers\Url;
use yii\helpers\Markdown;

$this->title = $model->title . ' | Bass-Alben | Katalog';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => Url::to(['catalog/overview'])];
$this->params['breadcrumbs'][] = ['label' => 'Bass-Alben', 'url' => Url::previous('album')];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="content">

    <h1 style="margin-bottom: 0.5rem"><?= $model->title ?></h1>

    <?php if (!empty($model->artist)) : ?>
        <p style="margin-bottom: 1.5rem">von <?= Html::a($model->artist, ['album/index', 'artist' => $model->artist]) ?></p>
    <?php endif; ?>

    <?php if (!empty($model->subtitle)) : ?>
        <p><?= $model->subtitle ?></p>
    <?php endif; ?>

    <?php if ($model->hasDefaultImage()) : ?>
        <p><?= Html::img($model->getDefaultImage('@web'), ["width" => 350, "class" => "img-fluid", "alt" => $model->fullTitle]) ?></p>
    <?php endif; ?>

    <div class="markdown"><?= Markdown::process($model->text) ?></div>

    <?php if (!empty($model->contents)) : ?>
        <h2>Inhalt</h2>
        <div class="markdown"><?= Markdown::process($model->contents) ?></div>
    <?php endif; ?>

    <?php if (!empty($model->blurb)) : ?>
        <h2>Klappentext</h2>
        <div class="markdown"><?= Markdown::process($model->blurb) ?></div>
    <?php endif; ?>

    <?php if (!empty($model->asin)) : ?>
        <p class="text-center"><a class="button button--big button--warning" href="<?= Url::toAmazonProduct($model->asin) ?>" target="_blank">Jetzt kaufen</a></p>
    <?php endif; ?>

    <?php if (!empty($tracklist = $model->getTracklistArray())) : ?>
        <h2>Tracklist</h2>
        <table class="table table--small">
            <tr>
                <th width="1%">Nr.</th>
                <th>Titel</th>
                <th class="text-end">Länge</th>
            </tr>
        <?php foreach ($tracklist as $track) : ?>
            <tr>
                <td class="text-end"><?= $track['number'] ?>.</td>
                <td><?= $track['title'] ?></td>
                <td class="text-end"><?= $track['duration'] ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php elseif (!empty($model->tracklist)) : ?>
        <h2>Tracklist</h2>
        <div class="markdown"><?= Markdown::process($model->tracklist) ?></div>
    <?php endif; ?>

    <?php /* if(!empty($model->asin)): ?>

        <div class="mp3-widget hidden-phone hidden-tablet">
            <h2>Anspielen und downloaden</h2>
            <script type='text/javascript'>
                var amzn_wdgt={widget:'MP3Clips'};
                amzn_wdgt.tag='electricbas03-21';
                amzn_wdgt.widgetType='ASINList';
                amzn_wdgt.ASIN='<?php echo $model->asin ?>';
                amzn_wdgt.title='';
                amzn_wdgt.width='234'; // 234
                amzn_wdgt.height='60'; // 60
                amzn_wdgt.shuffleTracks='True';
                amzn_wdgt.marketPlace='DE';
            </script>
            <script type='text/javascript' src='https://wms.assoc-amazon.de/20070822/DE/js/swfobject_1_5.js'></script>
        </div>
    */ ?>

    <?php if (!empty($model->bandcamp)) : ?>
        <h2>Anspielen und downloaden</h2>
        <div class="bandcamp-widget">
            <iframe width="300" height="100" style="background-color:#F3F4F3;position: relative; display: block; width: 300px; height: 100px;" src="https://bandcamp.com/EmbeddedPlayer/v=2/album=<?php echo $model->bandcamp ?>/size=grande/bgcol=F3F4F3/linkcol=4073A9/" allowtransparency="true" frameborder="0"></iframe>
        </div>

    <?php endif; ?>

    <?php if (!empty($model->productInfos)) : ?>
        <h2>Produktinformation</h2>
        <table class="table table--small">
            <?php foreach ($model->productInfos as $info) : ?>
                <tr>
                    <td class="col-md-4"><?= $info["label"] ?></td>
                    <td class="col-md-8"><?= $info["value"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?= $this->render('//_partials/meta', [
        'categories' => [
            ['label' => 'Katalog', 'url' => ['/catalog/overview']],
            ['label' => 'Bass-Alben', 'url' => Url::previous("album")]
        ],
        'tags' => $model->tags,
    ]); ?>

    <?= Rating::widget(["tableName" => "album", "tableId" => $model->id]) ?>

    <?= SocialBar::widget(["id" => $model->id, "text" => $model->title]) ?>

</div>

<?= Comments::widget(["tableName" => "album", "tableId" => $model->id]) ?>

<?= Hits::widget(["tableName" => "album", "tableId" => $model->id]) ?>

<?php if (!empty($similars)) : ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Ähnliche Musikalben</h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($similars as $model) : ?>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= $model->url ?>">
                    <strong><?= $model->title ?></strong><br>
                    <span class="text-muted"><?= $model->artist ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>
