Hallo <?php echo $email ?><br>
<br>
Du oder jemand anders hat dieses E-Mail zum Verwalten Deiner Inserate bei electricbass.ch angefordert.<br>
<br>
Dein(e) aktiven Inserat(e):<br>
<hr>
<?php foreach($models AS $model): ?>
<b><?php echo $model->title ?></b><br>
<?php echo $model->getShortenedText(100) ?><br>
<?php echo ($model->date) ?><br>
<a href="<?php echo $model->createDetailUrl(true) ?>">Ansehen</a>
 | <a href="<?php echo $model->createUpdateUrl() ?>">Bearbeiten</a>
 | <a href="<?php echo $model->createRenewUrl() ?>">Verlängern</a>
 | <a href="<?php echo $model->createDeleteUrl() ?>">Löschen</a><br>
<hr>
<?php endforeach ?>
<br>
Viel Erfolg beim Inserieren und Stöbern auf electricbass.ch!<br>
<br>
