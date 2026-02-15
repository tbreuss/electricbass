
<?php
/**
 * @var \yii\web\View $this
 * @var array<string, app\feature\links\models\Links[]> $linksByCategory
 */
?>
<?php yii\widgets\Spaceless::begin() ?>
<style>
    details summary {
        font-size: 1.2rem;
        font-weight: 500;
        line-height: 1.375;
        margin-top: 0;
        margin-bottom: 0.7rem;
    }
    details a {
        color: #5F5C52;
    }
    details td:nth-child(1) {
        padding-left: 0;
    }
    details td:nth-child(2) {
        text-align: center;
        width: 1%;
        padding-right: 0;
    }
</style>
<?php foreach ($linksByCategory as $category => $links): ?>
<details open>
    <summary id="<?= Nette\Utils\Strings::webalize($category) ?>"><?= $category ?></summary>
    <table class="table">
    <?php foreach ($links as $link): ?>
    <tr>
        <td><a rel="nofollow" href="<?= $link->website ?>"><?= $link->title ?></a></td>
        <td><img src="/media/flags/<?= $link->countryCode ?>.svg" alt="<?= $link->countryCode ?>" height="16"></td>
    </tr>
    <?php endforeach ?>
    </table>
</details>
<?php endforeach ?>

<ul class="table-of-contents">
    <?php foreach ($linksByCategory as $category => $links): ?>
        <li><a href="#<?= Nette\Utils\Strings::webalize($category) ?>"><?= $category ?></a></li>
    <?php endforeach ?>
</ul>
<?php yii\widgets\Spaceless::end() ?>
