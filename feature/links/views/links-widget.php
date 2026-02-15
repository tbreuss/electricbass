
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
    details td:nth-child(2) {
        text-align: center;
        width: 1%;
    }
</style>
<?php $isOpen = true ?>
<?php foreach ($linksByCategory as $category => $links): ?>
<details <?= $isOpen ? 'open' : '' ?>>
    <summary><?= $category ?></summary>
    <table class="table">
    <?php foreach ($links as $link): ?>
    <tr>
        <td>
            <a rel="nofollow" href="<?= $link->website ?>"><?= $link->title ?></a><br>
        </td>
        <td><img src="/media/flags/<?= $link->countryCode ?>.svg" alt="<?= $link->countryCode ?>" height="18"></td>
    </tr>
    <?php endforeach ?>
    </table>
    <?php $isOpen = false ?>
</details>
<?php endforeach ?>
<?php yii\widgets\Spaceless::end() ?>
