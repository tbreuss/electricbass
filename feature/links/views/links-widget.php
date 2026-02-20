
<?php
/**
 * @var \yii\web\View $this
 * @var array<int, array{title: string, subtitle: string, links: app\feature\links\models\Links[]}> $linkItems
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
<?php foreach ($linkItems as $item): ?>
<details open>
    <summary id="<?= Nette\Utils\Strings::webalize($item['title']) ?>"><?= $item['title'] ?></summary>
    <p><?= $item['subtitle'] ?></p>
    <table class="table">
    <?php foreach ($item['links'] as $link): ?>
    <tr>
        <td><a rel="nofollow" href="<?= $link->website ?>"><?= $link->title ?></a></td>
        <td><?php if ($link->countryCode !== ''): ?><img src="/media/flags/<?= $link->countryCode ?>.svg" alt="<?= $link->countryCode ?>" height="16"><?php endif ?></td>
    </tr>
    <?php endforeach ?>
    </table>
</details>
<?php endforeach ?>

<ul class="table-of-contents">
    <?php foreach ($linkItems as $item): ?>
        <li><a href="#<?= Nette\Utils\Strings::webalize($item['title']) ?>"><?= $item['title'] ?></a></li>
    <?php endforeach ?>
</ul>
<?php yii\widgets\Spaceless::end() ?>
