<?php
/**
 * @var app\entities\AtoZGroupedEntries[] $groupedEntries
 */
?>
<?php foreach ($groupedEntries as $group): ?>
    <h2><strong><?= $group->getInitial() ?></strong></h2>
    <ul>
        <?php foreach ($group->getEntries() as $entry): ?>
            <li>
                <a href="<?= $entry->getUrl() ?>"><?= $entry->getTitle() ?></a>
                <?php if ($entry->isNew()): ?>
                    <span class="is-new">NEU</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
