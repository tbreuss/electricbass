<?php
/**
 * @var View $this
 */

use app\helpers\Html;
use yii\web\View;

$this->title = 'Katalog';
$this->params['breadcrumbs'][] = 'Katalog';
$this->context->layout = 'onecol';
?>

<div class="content">

    <h1>Katalog</h1>

    <div class="widget widget-listview">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Lehrbücher für E-Bass', ['catalog/index', 'category' => 'lehrbuecher']) ?></h3>
                <p>Du möchtest E-Bass lernen und benötigst Material und Ideen zum Üben? Dann wirst du hier auf jeden Fall fündig. Wir haben rund 450 Lehrbücher in unserem Katalog.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Lehrbücher mit DVDs für E-Bass', ['catalog/index', 'category' => 'dvds']) ?></h3>
                <p>Bücher sind dir zu trocken, Du lernst eher visuell und am besten, wenn dir jemand etwas zeigt? In dieser Rubrik führen wir Lern-DVDs, die dir beim Lernen des E-Bass helfen könnten.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Allgemeine Bücher für Bassisten', ['catalog/index', 'category' => 'buecher']) ?></h3>
                <p>In dieser Rubrik führen wir allgemeine Bücher rund um das Thema E-Bass, Kontrabass und die Welt der tiefen Töne. Dabei geht es um Bässe, um bekannte Marken, Gitarrenbau, Instrumentenkunde oder einfach nur um den Bass.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Musikalben von E-Bassisten', ['album/index']) ?></h3>
                <p>In dieser Rubrik präsentieren wir immer wieder mal Musikalben von E-Bassisten. Frisches Zeugs zum Anhören. Dabei geht es nicht vorwiegend um die bekannten Bassisten dieser Welt (die kennt ja eh schon jeder), sondern auch um die Vorstellung von noch unbekannten Bands oder Musikern.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Videos von und für Bassisten', ['video/index']) ?></h3>
                <p>Hier gibt es immer wieder mal inspirierende Videos zum Thema Bass zu finden.
                    Darunter sind Videos zur Herstellung von E-Bässen, Videos mit Interpretationen bekannter Songs, Videos mit isolierten Basslinien und vieles mehr aus der Welt der Tieftöner.
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Websites zum Thema E-Bass', ['website/index']) ?></h3>
                <p>Neben Websites von Herstellern und Marken für E-Bass findet man hier auch immer wieder mal interessante Websites von Bassisten oder Links zu einer Website mit bassrelevantem Inhalt.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('E-Bass Hersteller und Marken', ['manufacturer/index']) ?></h3>
                <p>Hier geht es zu einer durchsuch- und filterbaren Liste mit Herstellern und Marken von E-Bässen, Akustikbässen, SUBs, Tonabnehmern, Lautsprechern, Verstärkern, Effekten und sonstigem Zubehör.</p>
            </div>
        </div>
    </div>

</div>
