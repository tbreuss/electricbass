<?php

use app\controllers\SearchController;
use app\helpers\Html;
use app\widgets\LinkPager;
use app\widgets\ListSummary;
use app\widgets\RatingReadOnly;
use yii\data\ActiveDataProvider;
use yii\helpers\Markdown;
use yii\helpers\Url;

$this->title = 'Suche';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = 'onecol_empty';

/**
 * @var ActiveDataProvider $dataProvider
 * @var bool $searched
 * @var string $term
 */

/**
 * @param string $text
 * @param string $words
 * @return string
 */
function highlightWords(string $text, string $words): string
{
    $words = explode(' ', $words);
    $words = array_map('trim', $words);
    foreach ($words as $word) {
        if (empty($word)) continue;
        $text = preg_replace('/\b(' . $word . ')\b/iu', '<span class="search-results__highlight">$1</span>', $text);
    }
    return $text;
}

?>

<div class="content">

    <h1>Suche</h1>

    <form class="search search--big" action="<?= Url::toRoute('/search/index') ?>" method="get">
        <input class="search__input" value="<?= Html::encode($term) ?>" type="text" name="term" placeholder="Suchen nach..." autofocus>
        <input class="search__submit" type="submit" value="Suchen">
    </form>

    <?php if (mb_strlen($term) === 0): ?>

        <?php if ($searched === true): ?>

            <p>Gib ein Suchwort ein.</p>

        <?php endif; ?>

        <p>
            Suche gezielt nach einem Beitrag.
            Gib dazu ein oder mehrere ganze Wörter ein.
            Beispiele: "Jaco Pastorius", "Rock Bass", "Tonleiter", "Lehrbuch", "Album"
        </p>

    <?php elseif (mb_strlen($term) < SearchController::MIN_QUERY_LENGTH): ?>

        <p>Die Suchanfrage muss mindestens drei Zeichen lang sein.</p>

    <?php elseif ($dataProvider->getTotalCount() === 0): ?>

        <p>Es wurden keine mit deiner Suchanfrage - <?= Html::encode($term) ?> - übereinstimmenden Dokumente
            gefunden.<br>
            <br>
            Vorschläge:</p>
        <ul>
            <li>Achte darauf, dass alle Wörter richtig geschrieben sind</li>
            <li>Probiere andere Suchbegriffe</li>
            <li>Probiere allgemeinere Suchbegriffe</li>
        </ul>

    <?php else: ?>

        <?php
        $models = $dataProvider->getModels();
        $pagination = $dataProvider->getPagination();
        ?>

        <?= ListSummary::widget(['pagination' => $dataProvider->getPagination(), 'sort' => $dataProvider->getSort()]) ?>

        <div class="search-results row">
            <?php foreach ($models as $i => $model): ?>
                <?php $isFirst = ($i === 0) ? 'isFirst' : ''; ?>
                <?php $isLast = (count($models) - 1 === $i) ? 'isLast' : ''; ?>
                <div class="search-results__item <?= $isFirst ?> <?= $isLast ?> col-sm-12">
                    <div class="search-results__title"><?= empty($model->url) ? highlightWords($model->title, $term) : Html::a(highlightWords($model->title, $term), $model->url) ?></div>
                    <?php echo RatingReadOnly::widget(["tableName" => $model->tableName, "tableId" => $model->tableId]) ?>
                    <div class="search-results__text">
                        <span class="text-muted"><?= highlightWords($model->contextText, $term) ?></span>
                        <?php if (!empty($model->abstract)): ?>
                            - <?= highlightWords(strip_tags(Markdown::process($model->abstract)), $term) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>

    <?php endif; ?>
</div>
