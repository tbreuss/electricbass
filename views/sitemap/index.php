<?php

/**
 * @var yii\web\View $this
 */

use app\helpers\Html;

$this->title = 'Sitemap';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sitemap">
    <h1 class="sitemap__title">Sitemap</h1>
    <h2 class="sitemap__section"><?= Html::a('Homepage', ['/'], ['class' => 'sitemap__link']) ?></h2>
    <h2 class="sitemap__section"><?= Html::a('Blog für E-Bass und Bassisten', ['/blog'], ['class' => 'sitemap__link']) ?></h2>
    <h2 class="sitemap__section"><?= Html::a('Lektionen, Übungen und Ideen für Bassisten', ['/lesson/index', 'path' => 'lektionen'], ['class' => 'sitemap__link']) ?></h2>
    <ul class="sitemap__links">
        <li><?= Html::a('Fitnessübungen für E-Bassisten', ['/lesson/index', 'path' => 'lektionen/fitness'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Tonleiterübungen für den E-Bass', ['/lesson/index', 'path' => 'lektionen/tonleiter'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Bass-Riffs aller Stilrichtungen', ['/lesson/index', 'path' => 'lektionen/bassriff'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Rhythmikübungen für Bassisten', ['/lesson/index', 'path' => 'lektionen/rhythmik'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Arpeggios für den E-Bass', ['/lesson/index', 'path' => 'lektionen/arpeggio'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Blues-Übungen für Bassisten', ['/lesson/index', 'path' => 'lektionen/blues'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Harmonielehre und Musiktheorie für E-Bass', ['/lesson/index', 'path' => 'lektionen/harmonielehre'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Walking Bass Übungen für E- und Kontrabass', ['/lesson/index', 'path' => 'lektionen/walking-bass'], ['class' => 'sitemap__link']) ?></li>
    </ul>
    <h2 class="sitemap__section"><?= Html::a('Tools', ['/tools'], ['class' => 'sitemap__link']) ?></h2>
    <ul class="sitemap__links">
        <li><?= Html::a('Fingersätze für Tonleitern, Intervalle und Akkorde', ['/fingering/index'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Online Metronom zum Üben', ['/tools/metronom'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Notenpapier zum Herunterladen und Ausdrucken', ['/tool/musicpaper'], ['class' => 'sitemap__link']) ?></li>
    </ul>
    <h2 class="sitemap__section"><?= Html::a('Katalog', ['/catalog/overview'], ['class' => 'sitemap__link']) ?></h2>
    <ul class="sitemap__links">
        <li><?= Html::a('Lehrbücher mit CDs für E-Bass', ['/catalog/index', 'category' => 'lehrbuecher'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Lehrbücher mit DVDs für E-Bass', ['/catalog/index', 'category' => 'dvds'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Bücher zum Thema Bass', ['/catalog/index', 'category' => 'buecher'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Musikalben von E-Bassisten', ['/catalog/index', 'category' => 'alben'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Videos von und für E-Bassisten', ['/video/index'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Websites zum Thema E-Bass', ['/website/index'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('E-Bass Hersteller und Marken', ['/manufacturer/index'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Witze über Bassisten', ['/joke/index'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Zitate berühmter Bassisten', ['/quote/index'], ['class' => 'sitemap__link']) ?></li>
    </ul>
    <h2 class="sitemap__section"><?= Html::a('Kleinanzeigen für Bassisten', ['/advertisement/index'], ['class' => 'sitemap__link']) ?></h2>
    <h2 class="sitemap__section">Diverses</h2>
    <ul class="sitemap__links">
        <li><?= Html::a('Impressum', ['/site/impressum'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Kontakt', ['/site/contact'], ['class' => 'sitemap__link']) ?></li>
        <li><?= Html::a('Suche', ['/search/index'], ['class' => 'sitemap__link']) ?></li>
    </ul>
</div>
