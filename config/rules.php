<?php

return [
    // Lektionen
    [
        'class' => 'app\components\LessonUrlRule',
    ],
    // Bassistenwitze
    'bassistenwitze' => 'joke/index',
    // Blog
    'blog' => 'blog/index',
    'blog/<id:[A-Za-z0-9-]+>' => 'blog/view',
    // Fingersätze
    'tools/fingersaetze' => 'fingering/index',
    'tools/fingersaetze/<id:[A-Za-z0-9-]+>' => 'fingering/view',
    'tools/metronom' => 'tool/metronome',
    // Glossar
    'glossar/reorder' => 'glossar/reorder',
    'glossar/<category>/<id>' => 'glossar/view',
    'glossar/<category>' => 'glossar/index',
    'glossar' => 'glossar/index',
    // Impressum
    'impressum' => 'site/impressum',
    // Katalog
    'katalog/hersteller' => 'manufacturer/index',
    'katalog/alben/index' => 'album/all',
    'katalog/alben/<id>' => 'album/view',
    'katalog/alben' => 'album/index',
    'katalog/<category>/index' => 'catalog/all',
    'katalog/<category>/<id>' => 'catalog/view',
    'katalog/<category>' => 'catalog/index',
    'katalog' => 'catalog/overview',
    // Kleinanzeigen
    'kleinanzeigen/' => 'advertisement/index',
    'kleinanzeigen/hinzufuegen' => 'advertisement/add',
    'kleinanzeigen/verwalten' => 'advertisement/manage',
    'kleinanzeigen/<id>' => 'advertisement/view',
    'advertisement/contact' => 'advertisement/contact',
    'advertisement/renew' => 'advertisement/renew',
    'advertisement/delete' => 'advertisement/delete',
    'advertisement/update' => 'advertisement/update',
    // Kontakt
    'kontakt' => 'site/contact',
    // Suche
    'suche' => 'search/index',
    // Tools
    'tools' => 'tool/index',
    'tools/notenpapier' => 'tool/musicpaper',
    // Websites
    'websites/index' => 'website/all',
    'websites/<id:[A-Za-z0-9-]+>' => 'website/view',
    'websites' => 'website/index',
    // Videos
    'videos' => 'video/index',
    'videos/<eid:[A-Za-z0-9-]+>' => 'video/view',
    // Sitemap
    'sitemap' => 'sitemap/index',
    'sitemap/google' => 'sitemap/google',
    // Zitate
    'zitate' => 'quote/index',
    // RSS
    'feed/rss' => 'feed/rss',
    // API
    'api/hits' => 'api/hits',
    'api/rate' => 'api/rate',
    'manufacturer/data' => 'manufacturer/data',
    'manufacturer/visit' => 'manufacturer/visit',
    'site/captcha' => 'site/captcha',
    // Admin
    'admin/<controller>/<action>' => 'admin/<controller>/<action>',
    'admin/<controller>' => 'admin/<controller>/index',
    'admin' => 'admin',
    // Pages
    [
        'class' => 'app\components\PageUrlRule',
    ],
    '' => 'site/index',
];
