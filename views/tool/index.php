<?php

/**
 * @var yii\web\View $this
 */

use app\helpers\Html;

$this->title = 'Tools';
$this->params['breadcrumbs'][] = 'Tools';

?>

<div class="content">

    <h1>Tools</h1>

    <div class="widget widget-listview">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Fingersätze', ['/fingering/index']) ?></h3>
                <p>
                    Bist du auf der Suche nach Fingersätzen für exotische Tonleitern oder Akkorde? Hier wirst du mit
                    Sicherheit fündig. Neben Fingersätzen für viele Tonleitern und Akkorde lernst du auch Fingersätze
                    für die (zweistimmigen) Intervalle.

                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Online Metronom', ['/tools/metronom']) ?></h3>
                <p>
                Einfach bedienbares Online Metronom, das beim Üben eine Hilfe sein kann, um dein Timing, deine Rhythmik und deine musikalische Genauigkeit zu verbessern.
                </p>
            </div>
        </div>
        <hr>        
        <div class="row">
            <div class="col-sm-12">
                <h3 class="title"><?= Html::a('Blanko Notenpapier zum Herunterladen und Ausdrucken', ['/tool/musicpaper']) ?></h3>
                <p>
                    Schreibe deine eigenen Basslininen, Leadsheets, Musiknoten oder Charts mit dem Notenpapier von uns.
                    Neben Notenblättern für Bassisten sind darunter auch solche für Gitarristen, Pianisten oder andere
                    Bass-Instrumentalisten zu finden.</p>
            </div>
        </div>
    </div>

</div>
