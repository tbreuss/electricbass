<?php

/**
 * @var View $this
 */

use yii\helpers\Url;
use yii\web\View;

$this->title = 'Impressum';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = 'onecol';
?>

<div class="content">

    <h1>Impressum</h1>

    <h2>Programmierung, Gestaltung, Redaktion</h2>
    <p>
        Thomas Breuss<br>
        Birsweg 5<br>
        4203 Grellingen<br>
        <a href="https://www.tebe.ch" target="_blank">www.tebe.ch</a>
    </p>

    <p>Falls Du mich kontaktieren willst, benutze bitte das <a
            href="<?= Url::to(["/site/contact"]) ?>">Kontaktformular</a>.</p>

    <h2>Co-Autoren</h2>
    <p>gemäss Angaben in den entsprechenden Artikeln</p>

    <h2>Haftungsausschluss</h2>
    <p>Der Autor übernimmt keinerlei Gewähr hinsichtlich der inhaltlichen Richtigkeit, Genauigkeit, Aktualität,
        Zuverlässigkeit und Vollständigkeit der Informationen.
        Haftungsansprüche gegen den Autor wegen Schäden materieller oder immaterieller Art, welche aus dem Zugriff oder
        der Nutzung bzw. Nichtnutzung der veröffentlichten Informationen, durch Missbrauch der Verbindung oder durch
        technische Störungen entstanden sind, werden ausgeschlossen.
        Alle Angebote sind unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte
        Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise
        oder endgültig einzustellen.
    </p>

    <h2>Haftung für Links</h2>
    <p>Verweise und Links auf Webseiten Dritter liegen ausserhalb unseres Verantwortungsbereichs. Es wird jegliche
        Verantwortung für solche Webseiten abgelehnt. Der Zugriff und die Nutzung solcher Webseiten erfolgen auf eigene
        Gefahr des Nutzers oder der Nutzerin.</p>

    <div class="werbung">
        <h3>Werbung</h3>
        <p>
            <a target="_blank" href="http://www.spanisch-sprachkurse-zuerich.ch/">Spanisch lernen in Zürich</a><br>
            <a href="https://herbie.tebe.ch" target="_blank">Website mit einfachen Textdateien erstellen - Herbie
                Flat-file CMS</a>
        </p>
    </div>

</div>
