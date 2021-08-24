<?php
/**
 * @var app\models\Advertisement[] $models
 * @var app\models\Advertisement $model
 */
?>
Hallo <?php echo $model->name ?><br>
<br>
Vielen Dank für das Aufschalten Deines Inserats <b>&laquo;<?php echo $model->title ?>&raquo;</b> bei electricbass.ch. Nach einer Laufzeit von 60 Tagen wird es automatisch gelöscht. Anfragen auf Dein Inserat erhälst Du direkt per E-Mail. Deine E-Mail wird nicht veröffentlicht.<br>
<br>
Dein(e) aktiven Inserat(e):<br>
<hr>
<?php foreach ($models as $model) : ?>
<b><?php echo $model->title ?></b><br>
    <?php echo $model->getShortenedText(100) ?><br>
    <?php echo ($model->date) ?><br>
<a href="<?php echo $model->createDetailUrl(true) ?>">Inserat ansehen</a>
 | <a href="<?php echo $model->createRenewUrl() ?>">Inserat verlängern</a>
 | <a href="<?php echo $model->createDeleteUrl() ?>">Inserat löschen</a><br>
<hr>
<?php endforeach ?>
<br>
Viel Erfolg beim Inserieren und Stöbern auf electricbass.ch!<br>
<br>
