<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use app\helpers\Html;

$this->title = 'Fehler';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">

    <h1>Fehler <?= $exception->statusCode ?></h1>

    <p>
        <?= nl2br(Html::encode($message)) ?> Probiere folgendes:
    </p>
    <ul>
        <li>Falls Du die URL in der Adresszeile des Browsers manuell eingetippt hast, achte auf korrekte Schreibweise.</li>
        <li>Öffne die <?= Html::a('Homepage', ['/']) ?>, um nach gewünschten Information zu suchen.</li>
        <li>Nutze die Navigation oben, um zur gewünschten Information zu gelangen.</li>
        <li>Nutze die <?= Html::a('interne Suche', ['/search/index']) ?>, um die gewünschte Information zu finden.</li>
        <li>Klicke den Zurück-Button des Browsers an.</li>
    </ul>

</div>
