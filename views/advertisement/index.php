<?php
/**
 * @var app\models\Advertisement[] $models
 * @var yii\web\View $this
 */

use app\helpers\Html;
use app\widgets\RatingReadOnly;
use yii\helpers\Markdown;
use yii\helpers\Url;

$this->title = 'Bassmarkt';
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageTitle'] = 'Kleinanzeigen für E-Bassist*innen - Gratis Inserate';
$this->params['metaDescription'] = 'Schwarzes Brett für Bassisten mit Inseraten für Musiker, Bands, Instrumente, Occassionen, Second-Hand... Kleinanzeigen erfassen und suchen. Kostenlos!';

?>

<div class="content">

	<h1><?= $this->title ?></h1>

	<?php if(Yii::$app->session->hasFlash('addFormSubmitted')): ?>
		<div class="flash flash--success">Vielen Dank! Dein Inserat ist freigeschaltet. Wir behalten uns vor, unpassende oder missbräuchliche Inserate zu löschen.</div>
	<?php endif; ?>

	<?php if(Yii::$app->session->hasFlash('deleteConfirmed')): ?>
		<div class="flash flash--success">Dein Inserat wurde gelöscht.</div>
	<?php endif; ?>

	<p>Gratis Inserate für Bassisten. Kleinanzeigen kostenlos erfassen und suchen. Marktplatz für E-Bassisten, Instrumente, Occassionen, Second-Hand, Zubehör, Verstärker und Effekte.</p>

	<div class="row">
		<div class="col-12 col-sm-6" style="margin-bottom:1rem">
			<a class="form__submit" href="<?= Url::toRoute('advertisement/add') ?>">Neues Inserat hinzufügen</a>
		</div>
        <div class="col-12 col-sm-6" style="margin-bottom:1rem">
            <a class="form__submit" href="<?= Url::toRoute('advertisement/manage') ?>">Deine Inserate verwalten</a>
        </div>
	</div>

	<?php /* Zähler */ ?>
	<?php $categories = array(); ?>
	<?php foreach($models AS $model): ?>
		<?php if(!array_key_exists($model->category_id, $categories)): ?>
			<?php $categories[$model->category_id] = array('title' => app\models\Advertisement::$categories[$model->category_id]); ?>
			<?php $categories[$model->category_id]['counter'] = 0; ?>
		<?php endif ?>
		<?php $categories[$model->category_id]['counter']++ ?>
	<?php endforeach; ?>

	<hr>

	<div class="widget widget-listview">
		<?php foreach ($models as $i => $model): ?>
			<?php if ($i > 0) echo "<hr>"; ?>
			<div class="row">
				<?php if (($photo=$model->getPhoto())!=''): ?>
					<div class="col-sm-3">
						<a href="<?= $model->url ?>"><?= Html::img('@web/' . $photo, ["width" => 280, "class" => "img-fluid", "alt" => $model->title]) ?></a>
					</div>
					<div class="col-sm-9">
						<h3 class="title"><a href="<?= $model->url ?>"><?= $model->title ?></a></h3>
						<?php /*<p class="text-muted"><span class="glyphicon glyphicon-lock"></span> Available Exclusively for Premium
							Members</p>*/ ?>
						<p class="text-muted">
							<span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDate($model->date, 'long') ?>
							/ <?= $model->countryTranslated ?>
						</p>
                        <?= RatingReadOnly::widget(["tableName" => "advertisement", "tableId" => $model->id]) ?>
						<p><?= mb_strimwidth(stripslashes(strip_tags(Markdown::process($model->longtext), '<italic>')), 0, 240, '...', 'UTF-8') ?></p>
						<?php /*<p class="text-muted">Presented by <a href="#">Ellen Richey</a></p>*/ ?>
					</div>
				<?php else: ?>
					<div class="col-sm-12">
						<h3 class="title"><a href="<?= $model->url ?>"><?= $model->title ?></a></h3>
						<p class="text-muted">
							<span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDate($model->date, 'long') ?>
							/ <?= $model->countryTranslated ?>
						</p>
                        <?= RatingReadOnly::widget(["tableName" => "advertisement", "tableId" => $model->id]) ?>
						<p><?= mb_strimwidth(stripslashes(strip_tags(Markdown::process($model->longtext), '<italic>')), 0, 240, '...', 'UTF-8') ?></p>
						<?php /*<p class="text-muted">Presented by <a href="#">Ellen Richey</a></p>*/ ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

</div>

<?php $this->beginBlock('sidebar') ?>
<div class="sidebarWidget">
    <h3 class="sidebarWidget__title">Kategorien</h3>
    <ul class="sidebarWidget__list">
        <?php foreach($categories AS $id=>$category): ?>
            <li class="sidebarWidget__item">
                <?= $category['title'] ?>
                <span><?= $category['counter'] ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php $this->endBlock() ?>
