<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use app\helpers\Html;

app\layouts\Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="scroll-behavior: auto !important;">
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
</head>
<body class="controller-<?= Yii::$app->controller->id ?> action-<?= Yii::$app->controller->id ?>-<?= isset(Yii::$app->controller->action) ? Yii::$app->controller->action->id : 'nil' ?>" itemscope itemtype="http://schema.org/WebPage">
<?php $this->beginBody() ?>
<main role="main">
     <div class="container-fluid container--content">
        <?= $content ?>
    </div>
</main>
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
