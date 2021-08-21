<?php
/**
 * @var app\models\Advertisement $model
 */
?>
Hallo!

Dein Inserat auf electricbass.ch wurde erfolgreich verlängert.
Nach einer Laufzeit von 60 Tagen wird es automatisch gelöscht.
Antworten auf Dein Inserat erhälst Du direkt per E-Mail.

Dein Inserat:
--------------------------------------------------------------------------------
<?php use app\helpers\Url;

echo $model->title ?><?php echo PHP_EOL ?>
<?php echo $model->getShortenedText() ?><?php echo PHP_EOL ?>
<?php echo $model->date ?><?php echo PHP_EOL ?>
<?php echo Url::to(['advertisement/view', array('id' => $model->id)], true) ?><?php echo PHP_EOL ?>
--------------------------------------------------------------------------------

Viel Erfolg beim Inserieren und Stöbern auf electricbass.ch!
