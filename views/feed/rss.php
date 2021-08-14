<?php
use app\models\Search;
use yii\helpers\Url;

// Siehe http://feeds.bbci.co.uk/news/world/rss.xml
?>
<?= '<?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0">
    <channel>
        <title>Electricbass.ch | Wissen und Lernen</title>
        <link>https://www.electricbass.ch</link>
        <description>Umfangreiche Informationen zum Thema E-Bass mit Artikeln, Lektionen, Bassriffs, einem Glossar, Lernvideos und vielem mehr.</description>
        <language>de</language>
        <pubDate><?= date(DATE_RSS, time()) ?></pubDate>
        <?php foreach ($query->each() as $row): ?>
            <?php $model = new Search($row); ?>
        <item>
            <title><![CDATA[<?= htmlspecialchars($model->title, ENT_XML1) ?>]]></title>
            <description><![CDATA[<?= htmlspecialchars($model->abstract, ENT_XML1) ?>]]></description>
            <link><?= Url::to($model->url, true) ?></link>
            <guid isPermaLink="true"><?= Url::to($model->url, true) ?></guid>
            <pubDate><?= date(DATE_RSS, strtotime($model->modified)) ?></pubDate>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>