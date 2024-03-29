<?php

/**
 * @var yii\db\Query $query
 */

use app\models\Search;
use yii\helpers\Markdown;
use yii\helpers\Url;

// Siehe http://feeds.bbci.co.uk/news/world/rss.xml
?>
<?= '<?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="<?= Url::to(['/feed/rss'], true) ?>" rel="self" type="application/rss+xml" />
        <title>Electricbass.ch | Wissen und Lernen</title>
        <link>https://www.electricbass.ch</link>
        <description>Umfangreiche Informationen zum Thema E-Bass mit Artikeln, Lektionen, Bassriffs, einem Glossar, Lernvideos und vielem mehr.</description>
        <language>de</language>
        <pubDate><?= date(DATE_RSS, time()) ?></pubDate>
        <?php foreach ($query->each() as $row): ?>
            <?php $model = new Search($row); ?>
            <item>
                <title><![CDATA[<?= strip_tags($model->title) ?>]]></title>
                <description><![CDATA[<?= Markdown::process($model->abstract, 'gfm') ?>]]></description>
                <link><?= Url::to($model->url, true) ?></link>
                <guid isPermaLink="true"><?= Url::to($model->url, true) ?></guid>
                <?php $timestamp = strtotime($model->modified) ?>
                <pubDate><?= ($timestamp === false) ? '' : date(DATE_RSS, $timestamp) ?></pubDate>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>