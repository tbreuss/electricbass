<?php

/**
 * @var yii\web\View $this
 * @var app\models\Website $website
 * @var app\models\Website[] $similars
 */

use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Parser;
use app\widgets\Rating;
use app\widgets\SocialBar;

$this->title = $website->title . ' | Websites';
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => Url::previous('website')];
$this->params['breadcrumbs'][] = $website->title;
?>

<div class="content">

    <h1><?= $website->title ?></h1>

    <?php if ($website->hasDefaultImage()): ?>
        <p><?= Html::img($website->getDefaultImage('@web'), ["class" => "img-fluid", "alt" => $website->title]) ?></p>
    <?php endif; ?>

    <?php if (!empty($website->website)): ?>
        <h2><?= Html::a($website->website, $website->website, ["target" => "_blank", "rel" => "nofollow"]) ?></h2>
    <?php endif; ?>

    <?php if (!empty($website->abstract)): ?>
        <div class="widget widget-parser">
            <?= Parser::widget(["model" => $website, "attribute" => "abstract"]) ?>
            <?php if (!empty($website->content)): ?>
                <?= Parser::widget(["model" => $website, "attribute" => "content"]) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($website->latitude) && !empty($website->longitude)): ?>
        <div class="map" style="clear:both">

            <h3>Standort</h3>
            <?php if (!empty($website->title) && !empty($website->subtitle)): ?>
                <p><?php echo $website->title ?>, <?= $website->subtitle ?></p>
            <?php endif; ?>

            <div id="map" style="height:250px;margin-bottom:1em;width:100%;"></div>

        </div>

        <script type="text/javascript">
            function initMap() {

                var options = {
                    mapTypeId: google.maps.MapTypeId.ROADMAP, // SATELLITE
                    disableDefaultUI: true
                };
                map = new google.maps.Map(document.getElementById("map"), options);
                var initialLocation = new google.maps.LatLng(<?php echo $website->latitude ?>, <?php echo $website->longitude ?>);
                map.setCenter(initialLocation);
                map.setZoom(10);

                var point = new google.maps.LatLng(<?php echo $website->latitude ?>, <?php echo $website->longitude ?>);
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    clickable: false
                });
            }
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBCysVBxBNAAbFzDAZQGTulZoImQNrhhI&callback=initMap">
        </script>
    <?php endif; ?>

    <?= $this->render('//_partials/meta', [
        'categories' => [
            ['label' => 'Katalog', 'url' => ['/catalog/overview']],
            ['label' => 'Bass-Websites', 'url' => Url::previous("website")]
        ],
        'tags' => $website->tags,
    ]); ?>

    <?= Rating::widget(["tableName" => "website", "tableId" => $website->id]) ?>


    <?= SocialBar::widget(["id" => $website->id, "text" => $website->title]) ?>

</div>

<?= Comments::widget(["tableName" => "website", "tableId" => $website->id]) ?>

<?= Hits::widget(["tableName" => "website", "tableId" => $website->id]) ?>

<?php if (!empty($similars)): ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Ähnliche Websites</h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($similars as $model): ?>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= $model->url ?>">
                    <strong><?= $model->title ?></strong><br>
                    <span class="text-muted"><?= join(', ', $model->getTagsAsArray(['Bass-Link'])) ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>
