<?php
/**
 * @var app\models\AdvertisementContactForm $model
 * @var app\models\Advertisement $advertisement
 * @var string $email
 */
?>
<?php echo nl2br($model->message) ?>
<br>
<?php if (!empty($model->phone)) :
    ?><br>Telefon: <?php echo $model->phone ?><br><?php
endif; ?>
<br>
<br>
<hr>
Dein aktives Inserat:<br>
<br>
<b><?php echo $advertisement->title ?></b><br>
<?php echo $advertisement->getShortenedText(100) ?><br>
<?php #echo Html::dateTime($advertisement->date) ?><br>
<a href="<?php echo $advertisement->createDetailUrl(true) ?>">Inserat ansehen</a><br>
<hr>
<br>
Viel Erfolg beim Inserieren und Stöbern auf electricbass.ch!<br>
<br>
