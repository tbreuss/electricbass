<?php

/**
 * @var string $name
 * @var string $message
 * @var yii\web\View $this
 * @var yii\web\HttpException $exception
 */

use app\helpers\Html;

$this->title = 'Fehler';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">
    <h1>Fehler <?= $exception->statusCode ?></h1>
    <?php $message = $exception->getMessage() ?>
    <?php if (($message === '') || str_contains($exception->getFile(), 'yii2/web/ViewAction.php')): ?>
        <p>Die angeforderte URL <?= $_SERVER['REQUEST_URI'] ?> wurde auf diesem Server nicht gefunden.</p>
    <?php else: ?>
        <p><?= nl2br(Html::encode($message)) ?></p>
    <?php endif; ?>
    <p>Probiere folgendes:</p>
    <ul>
        <li>Falls Du die URL in der Adresszeile des Browsers manuell eingetippt hast, achte auf korrekte Schreibweise.</li>
        <li>Öffne die <?= Html::a('Homepage', ['/']) ?>, um nach gewünschten Information zu suchen.</li>
        <li>Nutze die Navigation oben, um zur gewünschten Information zu gelangen.</li>
        <li>Nutze die <?= Html::a('interne Suche', ['/search/index']) ?>, um die gewünschte Information zu finden.</li>
        <li>Klicke den Zurück-Button des Browsers an.</li>
    </ul>

</div>
