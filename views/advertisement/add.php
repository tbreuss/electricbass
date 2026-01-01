<?php

/**
 * @var app\models\Advertisement $model
 * @var yii\web\View $this
 */

$this->title = 'Inserat hinzufügen | Bassmarkt';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserat hinzufügen';
?>

<div class="content">

    <h1>Inserat hinzufügen</h1>

    <p>Keine Anmeldung notwendig! Einfach das Inserat erfassen und fertig!<br />
    Die Inserate haben eine Laufzeit von 60 Tagen.</p>

    <?php // $this->render('_form', ['model' => $model]) ?>
    <div class="col-12 flash flash--warning">Wegen Spam können zur Zeit keine neuen Inserate erfasst werden.</div>

</div>
