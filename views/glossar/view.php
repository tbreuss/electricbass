<?php
use app\helpers\Html;
use app\widgets\Comments;
use app\widgets\Rating;
use app\widgets\SocialBar;
use app\widgets\Hits;
use yii\helpers\Markdown;

?>

<?php
$this->title = $glossar->title . ' | ' . $glossar->category . ' | Glossar';
$this->params['breadcrumbs'][] = ['label' => 'Glossar', 'url' => ['glossar/index']];
$this->params['breadcrumbs'][] = ['label' => $glossar->category, 'url' => ['glossar/index', 'category' => strtolower($glossar->category)]];;
$this->params['breadcrumbs'][] = $glossar->title;
?>

<div class="content">

    <h1><?php echo $glossar->title ?></h1>
    <?php #$this->metaDescription = strip_tags(Markdown::process($glossar->abstract)) ?>

    <p>In <?php echo Html::a('Glossar', array('/glossar')) ?> / <?php echo Html::a($glossar->category, array('/glossar/index', 'category' => $selectedCategory)) ?></p>

    <?php if ($glossar->hasDefaultImage()): ?>
        <p><?= Html::img($glossar->getDefaultImage('@web', ["width" => 350, "class" => "img-fluid", "alt" => $glossar->title])) ?></p>
    <?php endif; ?>

    <?php
    echo Markdown::process($glossar->content);
    ?>

    <ul class="pager">
        <?php if ($next != null): ?>
            <li class="next">
                <?php echo Html::a($next->title, $next->url, array('class' => 'button button--small')) ?>
            </li>
        <?php endif; ?>
        <?php if ($previous !== null): ?>
            <li class="previous">
                <?php echo Html::a($previous->title, $previous->url, array('class' => 'button button--small')) ?>
            </li>
        <?php endif; ?>
    </ul>

    <?php echo $this->render('_sidebar', array('glossar' => $glossar, 'selectedCategory' => $selectedCategory)) ?>

    <?= Rating::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

    <?= SocialBar::widget(["id" => $glossar->id, "text" => $glossar->title]) ?>

    <?= Hits::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

    <?= Comments::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

</div>

<style>
    .pager .next {
        float:right;
    }
    .pager {
        list-style-type:none;
        margin:0;
        padding:0;
    }
</style>
