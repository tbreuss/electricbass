<?php
/**
 * @var app\models\Advertisement $model
 */
?>
Hallo <?php echo $model->name ?><br>
<br>
Dein Inserat bei electricbass.ch ist abgelaufen. Mit Klick auf den Link &laquo;Inserat verlängern&raquo; kannst du das Inserat um weitere 60 Tage verlängern.<br>
<br>
<hr>
<b><?php echo $model->title ?></b><br>
<?php echo $model->getShortenedText(100) ?><br>
<?php #echo Html::dateTime($model->date) ?><br>
<a href="<?php echo $model->createDetailUrl(true) ?>">Inserat ansehen</a>
 | <a href="<?php echo $model->createRenewUrl() ?>">Inserat verlängern</a><br>
<hr>
<br>
Viel Erfolg beim Inserieren und Stöbern auf electricbass.ch!<br>
<br>
