<?php

/**
 * @var yii\web\View $this
 * @var array<string|string[]> $papers
 */

$this->params['pageTitle'] = 'Leeres Notenpapier als PDF herunterladen und ausdrucken';
$this->params['metaDescription'] = 'Blanko Notenpapier zum Herunterladen und Ausdrucken ✓ PDFs mit Notenlinien ✚ Für alle Instrumente ✚ Für E-Bass und Gitarre mit Tabulatur ➨ Gratis!';
$this->params['breadcrumbs'][] = ['label' => 'Werkzeuge', 'url' => '/tools'];
$this->params['breadcrumbs'][] = 'Leeres Notenpapier als PDF';
?>

<div class="content">
    <h1>Notenpapier zum Herunterladen und Ausdrucken</h1>
    <p class="lead">
        Blanko Notenpapier für Bass, Gitarre, Klavier und andere Instrumente!
        Lade die Notenblätter im PDF-Format kostenlos herunter und drucke sie anschliessend auf deinem Drucker aus.
        Schreibe damit Musiknoten, Gesangs- und Basslinien oder Leadsheets für deine Band.
        Die Notenblätter eignen sich ideal zum Transkribieren, Komponieren, Arrangieren, Notizen erstellen oder für andere Zwecke.
        Das alles gratis und ohne Kosten!
    </p>
    <div class="widget widget-listview">
    <?php foreach ($papers as $paper): ?>
        <?php if (is_string($paper)): ?>
            <h2><?= $paper ?></h2>
        <?php else: ?>
            <?php
                $urlPdf = Yii::getAlias('@web/media/tools/notenpapier/' . $paper[0]);
            if ($urlPdf === false) {
                continue;
            }
                $urlImg = str_replace('.pdf', '.png', $urlPdf);
            ?>
            <div class="row" style="margin-bottom:20px">
                <div class="col-sm-3">
                    <img class="img-fluid" src="<?= $urlImg ?>" width="200" alt="<?= $paper[1] ?>">
                </div>
                <div class="col-sm-9">
                    <h3 style="margin-top:0"><?= app\helpers\Html::a($paper[1] . ' (' . $paper[2] . ')', $urlPdf, ['target' => '_blank']) ?></a></h3>
                    <p><?= $paper[3] ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
</div>
