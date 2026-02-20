<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use app\assets\AppAsset;
use app\helpers\Html;
use app\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);

if (!empty($this->params['metaDescription'])) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => Html::encode($this->params['metaDescription'])
    ]);
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="scroll-behavior: auto !important;">
<head>
    <?php if (!YII_ENV_DEV): /* @phpstan-ignore-line */ ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-2624727-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-2624727-1');
        </script>
    <?php endif; ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" href="/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <?php if (!empty($this->params['pageTitle'])): ?>
        <title><?= Html::encode($this->params['pageTitle']) ?></title>
    <?php else: ?>
        <title><?= Html::encode($this->title) ?> | Electricbass.ch</title>
    <?php endif; ?>
    <?php $this->head() ?>
</head>
<body class="controller-<?= Yii::$app->controller->id ?> action-<?= Yii::$app->controller->id ?>-<?= isset(Yii::$app->controller->action) ? Yii::$app->controller->action->id : 'nil' ?>" itemscope itemtype="http://schema.org/WebPage">
<?php $this->beginBody() ?>
<main role="main">
    <?php if (YII_ENV_DEV): /* @phpstan-ignore-line */ ?>
        <div class="sizer">
            <div class="d-sm-none">X-SMALL</div>
            <div class="d-none d-sm-block d-md-none">SMALL</div>
            <div class="d-none d-md-block d-lg-none">MEDIUM</div>
            <div class="d-none d-lg-block d-xl-none">LARGE</div>
            <div class="d-none d-xl-block d-xxl-none">EXTRA LARGE</div>
            <div class="d-none d-xxl-block">EXTRA EXTRA LARGE</div>
        </div>
    <?php endif; ?>
    <nav class="navbar">
        <div class="container">
            <a class="navbar__logo" href="<?= Yii::$app->homeUrl ?>">
                <img src="/img/logo.png" alt="Logo" width="21" height="21">
                <span>ELECTRICBASS</span>
            </a>
            <ul class="navbar__menu">
                <li class="navbar__menu-item">
                    <a class="navbar__menu-link" href="<?= Url::to(['/blog/index']) ?>">Blog</a>
                </li>
                <li class="navbar__menu-item navbar-dropdown">
                    <a class="navbar__menu-link navbar-dropdown__button" href="#">Lektionen</a>
                    <ul class="navbar-dropdown__content">
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/fitness']) ?>">Fingerübungen</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/tonleiter']) ?>">Tonleitern</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/bassriff']) ?>">Bass-Riffs</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/rhythmik']) ?>">Rhythmus</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/arpeggio']) ?>">Arpeggios</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/blues']) ?>">Blues</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/harmonielehre']) ?>">Harmonielehre</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'lektionen/walking-bass']) ?>">Walking Bass</a></li>
                    </ul>
                </li>
                <li class="navbar__menu-item navbar-dropdown">
                    <a class="navbar__menu-link navbar-dropdown__button" href="#">Werkzeuge</a>
                    <ul class="navbar-dropdown__content">
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/fingering/index']) ?>">Fingersätze</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/tool/metronome']) ?>">Metronom</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/tool/musicpaper']) ?>">Notenpapier</a></li>
                    </ul>
                </li>
                <li class="navbar__menu-item navbar-dropdown">
                    <a class="navbar__menu-link navbar-dropdown__button" href="#">Katalog</a>
                    <ul class="navbar-dropdown__content">
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/catalog/index', 'category' => 'lehrbuecher']) ?>">Lehrbücher</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/catalog/index', 'category' => 'dvds']) ?>">Lehrbücher mit DVDs</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/catalog/index', 'category' => 'buecher']) ?>">Bücher zum Thema Bass</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/catalog/index', 'category' => 'alben']) ?>">Bass-Alben</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/video/index']) ?>">Bass-Videos</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'links']) ?>">Linkverzeichnis</a></li>
                    </ul>
                </li>
                <li class="navbar__menu-item">
                    <a class="navbar__menu-link" href="<?= Url::to(['/advertisement/index']) ?>">Kleinanzeigen</a>
                </li>
                <li class="navbar__menu-item navbar-dropdown">
                    <a class="navbar__menu-link" href="#"><img class="navbar-dropdown__button" src="/img/search.png" alt="Suchen" height="21"></a>
                    <ul class="navbar-dropdown__content">
                        <li class="navbar-dropdown-item">
                            <form class="search" action="<?= Url::toRoute('/search/index') ?>" method="get">
                                <input class="search__input navbar-dropdown__search" type="text" name="term" placeholder="Suchen nach...">
                                <input class="search__submit" type="submit" value="Suchen">
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container navbar-border-bottom"><div class="border"></div></div>

    <?php if (!empty($this->params['breadcrumbs'])): ?>
    <div class="container breadcumbList">
        <a class="breadcumbList__home" href="javascript:window.location.href='<?= Url::home() ?>'">Start</a>
        <ol vocab="https://schema.org/" typeof="BreadcrumbList">
            <?php foreach ($this->params['breadcrumbs'] as $itemCount => $item): ?>
                <?php if (is_array($item)): ?>
                <li property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" href="<?= Url::to($item['url']) ?>">
                    <span property="name"><?= $item['label'] ?></span></a>
                    <meta property="position" content="<?= $itemCount + 1 ?>">
                </li>
                <?php endif; ?>
                <?php if (is_string($item)): ?>
                <li property="itemListElement" typeof="ListItem">
                    <span property="name"><?= $item ?></span>
                    <meta property="position" content="<?= $itemCount + 1 ?>">
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>

    <?php if (isset($this->blocks['jumbotron'])): ?>
        <?= $this->blocks['jumbotron'] ?>
    <?php endif; ?>

    <div class="container container--content">
        <?= $content ?>
    </div>

</main>
<footer class="footer">
    <div class="container">
        <div class="row links">
            <div class="col-md-5 col-xs-10">
                <h5 class="footer__title">Über ELECTRICBASS</h5>
                <p class="footer__text">ELECTRICBASS ist ein Blog und eine Website für E-Bassisten und Bassistinnen und bietet umfangreiche Informationen zum Thema Bass, Kontrabass und E-Bass, dem bässten Instrument der Welt!</p>
            </div>
            <div class="col-md-3 offset-md-1 col-xs-6">
                <h5 class="footer__title">Kontakt &amp; Info</h5>
                <?= Menu::widget([
                    'options' => ['class' => 'footer__list'],
                    'items' => [
                        ['label' => 'Impressum', 'url' => ['/lesson/index', 'path' => 'impressum']],
                        ['label' => 'Kontakt', 'url' => ['/site/contact']],
                        ['label' => 'Sitemap', 'url' => ['/lesson/index', 'path' => 'sitemap']],
                        ['label' => 'Facebook', 'url' => 'https://www.facebook.com/ElectricBassCH', 'template' => '<a href="{url}" target="_blank" rel="nofollow">{label}</a>'],
                        ['label' => 'Twitter', 'url' => 'https://twitter.com/ElectricBassCH', 'template' => '<a href="{url}" target="_blank" rel="nofollow">{label}</a>'],
                    ]]); ?>
            </div>
            <div class="col-md-3 col-xs-6">
                <form class="search" action="/suche" method="get">
                    <input class="search__input navbar-dropdown__search" type="text" name="term" placeholder="Suchen nach...">
                    <input class="search__submit" type="submit" value="Suchen">
                </form>
            </div>
        </div>
        <?php if (Yii::$app->controller->id === 'site' && isset(Yii::$app->controller->action) && Yii::$app->controller->action->id === 'index'): ?>
        <div class="row">
            <div class="col-md-12">
                <hr class="footer__hr">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <h5 class="footer__title">Lektionen, Übungen & Ideen</h5>
                <ul class="footer__list">
                    <li><?= Html::a('Fingerübungen für E-Bassisten', ['/lesson/index', 'path' => 'lektionen/fitness']) ?></li>
                    <li><?= Html::a('Tonleiterübungen für den E-Bass', ['/lesson/index', 'path' => 'lektionen/tonleiter']) ?></li>
                    <li><?= Html::a('Bass-Riffs aller Stilrichtungen', ['/lesson/index', 'path' => 'lektionen/bassriff']) ?></li>
                    <li><?= Html::a('Rhythmusübungen für Bassisten', ['/lesson/index', 'path' => 'lektionen/rhythmik']) ?></li>
                    <li><?= Html::a('Arpeggios für den E-Bass', ['/lesson/index', 'path' => 'lektionen/arpeggio']) ?></li>
                    <li><?= Html::a('Blues-Übungen für Bassisten', ['/lesson/index', 'path' => 'lektionen/blues']) ?></li>
                    <li><?= Html::a('Harmonielehre und Musiktheorie für E-Bass', ['/lesson/index', 'path' => 'lektionen/harmonielehre']) ?></li>
                    <li><?= Html::a('Walking Bass Übungen für E- und Kontrabass', ['/lesson/index', 'path' => 'lektionen/walking-bass']) ?></li>
                </ul>
            </div>
            <div class="col-12 col-md-4">
                <h5 class="footer__title">Kataloge & mehr</h5>
                <ul class="footer__list">
                    <li><?= Html::a('Lehrbücher mit CDs für E-Bass', ['/catalog/index', 'category' => 'lehrbuecher']) ?></li>
                    <li><?= Html::a('Lehrbücher mit DVDs für E-Bass', ['/catalog/index', 'category' => 'dvds']) ?></li>
                    <li><?= Html::a('Bücher zum Thema Bass', ['/catalog/index', 'category' => 'buecher']) ?></li>
                    <li><?= Html::a('Musikalben von E-Bassisten', ['/catalog/index', 'category' => 'alben']) ?></li>
                    <li><?= Html::a('Videos von und für E-Bassisten', ['/video/index']) ?></li>
                    <li><?= Html::a('Linkverzeichnis zum Thema E-Bass', ['/lesson/index', 'path' => 'links']) ?></li>
                    <li><?= Html::a('Witze über Bassisten', ['/joke/index']) ?></li>
                    <li><?= Html::a('Zitate berühmter Bassisten', ['/quote/index']) ?></li>
                </ul>
            </div>
            <div class="col-12 col-md-4">
                <h5 class="footer__title">weitere Infos</h5>
                <div class="sitemap">
                    <ul class="footer__list">
                        <li><?= Html::a('Blog für E-Bass und Bassisten', ['/blog']) ?></li>
                        <li><?= Html::a('Fingersätze für Tonleitern, Intervalle und Akkorde', ['/fingering/index']) ?></li>
                        <li><?= Html::a('Einfaches Online Metronom', ['/tool/metronome']) ?></li>
                        <li><?= Html::a('Notenpapier zum Herunterladen und Ausdrucken', ['/tool/musicpaper']) ?></li>
                        <li><?= Html::a('Kleinanzeigen für Bassisten', ['/advertisement/index']) ?></li>
                        <li><?= Html::a('Glossar für Bassisten', ['/glossar/index']) ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <hr class="footer__hr">
            </div>
        </div>
        <div class="row footer__copy">
            <div class="col-md-6">
                <p class="footer__text">&copy; 1998-<?= date('Y') ?> ELECTRICBASS</p>
            </div>
            <div class="col-md-6">
                <p class="text-end footer__text">Ein <a target="_blank" href="https://tebe.ch">tebe.ch</a> Projekt</p>
            </div>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // move table of contents to sidebar
        const tableOfContents = document.querySelector(".table-of-contents");
        const sidebar = document.querySelector(".sidebar__inner");
        if (tableOfContents && sidebar) {
            sidebar.prepend(tableOfContents);
        }
        // open all external links in a new tab or window
        const currentHost = window.location.hostname.replace('https://', '').replace('http://', ''). replace('www.', '');
        const links = document.querySelectorAll("a");
        links.forEach(link => {
            if (link.hostname) {
                const linkHostname = link.hostname.replace('https://', '').replace('http://', ''). replace('www.', '');
                if (linkHostname !== currentHost) {
                    if (link.getAttribute("target") === null) {
                        link.setAttribute("target", "_blank");
                    }
                    if (link.getAttribute("rel") === null) {
                        link.setAttribute("rel", "noopener noreferrer");
                    }
                }
            }
        });
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
