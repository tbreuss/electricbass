<?php /** @var string $hostInfo */ ?>
<?php /** @var array $urls */ ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <?php foreach ($urls as $loc => $images): ?>
    <url>
        <loc><?= $hostInfo ?><?= $loc ?></loc>
        <?php foreach ($images as $image): ?>
        <image:image>
            <image:loc><?= $hostInfo ?><?= $image['loc'] ?></image:loc>
            <image:title><?= $image['title'] ?></image:title>
        </image:image>
        <?php endforeach ?>
    </url>
    <?php endforeach ?>
</urlset>
