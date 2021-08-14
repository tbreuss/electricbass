<?php

use app\helpers\Url;
use app\models\Search;

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

foreach ($staticPages as $page) {
    echo '<url>';
    echo '<loc>' . $page[0] . '</loc>';
    echo '<lastmod>' . $page[1] . '</lastmod>';
    echo '</url>';
}

foreach ($query->each() as $row) {
    $model = new Search($row);
    echo '<url>';
    echo '<loc>' . Url::to($model->url, true) . '</loc>';
    echo '<lastmod>' . $model->getLastModAsAtom() . '</lastmod>';
    echo '</url>';
}
echo '</urlset>';
