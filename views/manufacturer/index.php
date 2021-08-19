<?php

/**
 * @var yii\web\View $this
 * @var array $manufacturers
 */

use app\assets\ManufacturerAsset;
use app\helpers\Url;

$this->title = 'Hersteller';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => ['catalog/overview']];
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = 'empty';

ManufacturerAsset::register($this);

?>

<div class="row">
    <div class="col-md-12">
        <div id="ctrl-manufacturer-index">
            <h1>E-Bass Hersteller und Marken</h1>
            <noscript>
                <div class="flash flash--warning">Hinweis: Für eine bessere Nutzererfahrung JavaScript im Browser aktivieren.</div>
                <ul><?php foreach ($manufacturers as $m): ?>
                    <li><?php echo $m['name'] ?></li>
                <?php endforeach; ?></ul>
            </noscript>
            <p>Lade Inhalte...</p>
        </div>
    </div>
</div>

<?php
$dataUrl = Url::to(['/manufacturer/data']);
$js = <<<JS
    manufacturer({
        dataUrl: '$dataUrl'
    });
JS;
$this->registerJs($js);
?>
