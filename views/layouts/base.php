<?php

/* @var $this \yii\web\View */
/* @var $content string */

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
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php if (!YII_ENV_DEV): ?>
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
    <?php if (!YII_ENV_DEV): ?>
        <script data-ad-client="ca-pub-6295275013493411" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <?php endif; ?>
</head>
<body class="controller-<?= Yii::$app->controller->id ?> action-<?= Yii::$app->controller->id ?>-<?= Yii::$app->controller->action->id ?>" itemscope itemtype="http://schema.org/WebPage">
<?php $this->beginBody() ?>
<main role="main">
    <?php if (YII_ENV_DEV): ?>
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
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'fitness']) ?>">Fitness</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'tonleiter']) ?>">Tonleitern</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'bassriff']) ?>">Bass-Riffs</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'rhythmik']) ?>">Rhythmik</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'arpeggio']) ?>">Arpeggios</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'blues']) ?>">Blues</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'harmonielehre']) ?>">Harmonielehre</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/lesson/index', 'path' => 'walking-bass']) ?>">Walking Bass</a></li>
                    </ul>
                </li>
                <li class="navbar__menu-item navbar-dropdown">
                    <a class="navbar__menu-link navbar-dropdown__button" href="#">Tools</a>
                    <ul class="navbar-dropdown__content">
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/fingering/index']) ?>">Fingersätze</a></li>
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
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/website/index']) ?>">Bass-Websites</a></li>
                        <li class="navbar-dropdown-item"><a href="<?= Url::to(['/manufacturer/index']) ?>">Bass-Hersteller</a></li>
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
            <div class="col-md-4 col-xs-10">
                <h5 class="footer__title">Über ELECTRICBASS</h5>
                <p class="footer__text">ELECTRICBASS ist eine private Website von Thomas Breuss und bietet umfangreiche Informationen über
                    das bässte Instrument der Welt!</p>
            </div>
            <div class="col-md-3 offset-md-2 col-xs-6">
                <h5 class="footer__title">Kontakt &amp; Info</h5>
                <?= Menu::widget([
                    'options' => ['class' => 'footer__list'],
                    'items' => [
                        ['label' => 'Impressum', 'url' => ['/site/impressum']],
                        ['label' => 'Kontakt', 'url' => ['/site/contact']],
                        ['label' => 'Sitemap', 'url' => ['/sitemap/index']],
                        ['label' => 'Facebook', 'url' => 'https://www.facebook.com/ElectricBassCH', 'template' => '<a href="{url}" target="_blank" rel="nofollow">{label}</a>'],
                        ['label' => 'Twitter', 'url' => 'https://twitter.com/ElectricBassCH', 'template' => '<a href="{url}" target="_blank" rel="nofollow">{label}</a>'],
                    ]]); ?>
            </div>
            <div class="col-md-3 col-xs-6">
                <h5 class="footer__title">Links &amp; mehr</h5>
                <?= Menu::widget([
                    'options' => ['class' => 'footer__list'],
                    'items' => [
                        ['label' => 'Bass-Glossar', 'url' => ['/glossar/index']],
                        ['label' => 'Bass-Websites', 'url' => ['/website/index']],
                        ['label' => 'Bassisten-Witze', 'url' => ['/joke/index']],
                        ['label' => 'Zitate von Bassisten', 'url' => ['/quote/index']],
                        ['label' => 'Kleinanzeigen', 'url' => ['/advertisement/index']],
                        ['label' => 'Suche', 'url' => ['/search/index']],
                    ]]); ?>
            </div>
        </div>
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
                <p class="text-end footer__text">Mit Herzblut, <a target="_blank" rel="nofollow" href="http://www.yiiframework.com">Yii2</a> und <a href="http://getbootstrap.com/" target="_blank" rel="nofollow">Bootstrap</a> gemacht.</p>
            </div>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
