<?php

namespace app\features\musicPaper;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        $this->layout = 'onecol';
        return $this->render('@app/features/musicPaper/views/index', [
            'papers' => [
                'Für E-Bassisten',
                [
                    'notenpapier-bassschluessel-tabulatur-4-saiter.pdf',
                    '4-Saiter Bass TAB Notenpapier',
                    'PDF',
                    'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 4-Saiter. DIN A4-Format.'
                ],
                [
                    'notenpapier-bassschluessel-tabulatur-5-saiter.pdf',
                    '5-Saiter Bass TAB Notenpapier',
                    'PDF',
                    'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 5-Saiter. DIN A4-Format.'
                ],
                [
                    'notenpapier-bassschluessel-tabulatur-6-saiter.pdf',
                    '6-Saiter Bass TAB Notenpapier',
                    'PDF',
                    'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 6-Saiter. DIN A4-Format.'
                ],
                [
                    'notenpapier-tabulatur-4-saiter.pdf',
                    '4-Saiter Tabulatur Notenpapier',
                    'PDF',
                    'Für Bassisten: Zwölf Systeme mit Tabulatur für 4-saitige Instrumente. DIN A4-Format.'
                ],
                [
                    'notenpapier-tabulatur-5-saiter.pdf',
                    '5-Saiter Tabulatur Notenpapier',
                    'PDF',
                    'Für Bassisten: Zwölf Systeme mit Tabulatur für 5-saitige Instrumente. DIN A4-Format.'
                ],
                [
                    'notenpapier-tabulatur-6-saiter.pdf',
                    '6-Saiter Tabulatur Notenpapier',
                    'PDF',
                    'Für Bassisten: Zehn Systeme mit Tabulatur für 6-saitige Instrumente. DIN A4-Format.'
                ],
                'Für andere Bassisten',
                [
                    'notenpapier-bassschluessel.pdf',
                    'Leeres Bassschlüssel Notenpapier',
                    'PDF',
                    'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit zwölf Systemen und Bassschlüssel. DIN A4-Format.'
                ],
                [
                    'notenpapier-bassschluessel-32-takte.pdf',
                    'Leeres Bassschlüssel Notenpapier 32 Takte',
                    'PDF',
                    'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit acht Systemen und vier Takten pro System im
                    Bassschlüssel. Dieses Notenpapier eignet sich ideal für Jazz-Standards in 32-taktigen Formen. Ein Chorus (in der
                    Form AABA oder ABAB) passt somit genau auf ein Notenblatt. Schreibe deine Walking Basslines oder transkribiere
                    abgefahrene Basssolos. DIN A4-Format.'
                ],
                [
                    'notenpapier-bassschluessel-taktstriche.pdf',
                    'Leeres Bassschlüssel Notenpapier',
                    'PDF',
                    'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit zwölf Systemen und vier Takten pro System im
                    Bassschlüssel. Dieses Notenpapier ist perfekt, um zum Beispiel Walking Basslines zu transkribieren. DIN
                    A4-Format.'
                ],
                'Für Gitarristen',
                [
                    'notenpapier-gitarre-tabulatur.pdf',
                    'Gitarre TAB Notenpapier',
                    'PDF',
                    'Für Gitarristen: Sechs Systeme mit Violinschlüssel und Tabulatur für Gitarre. DIN A4-Format.'
                ],
                'Für Pianisten',
                [
                    'notenpapier-klavier.pdf',
                    'Klavier Notenpapier',
                    'PDF',
                    'Für Pianisten: Leeres Notenpapier für Klavier mit sechs Systemen (Violin- und Basschlüssel). DIN A4-Format.'
                ],
                'Für jedermann',
                [
                    'notenpapier.pdf',
                    'Leeres Notenpapier',
                    'PDF',
                    'Für jedermann: Leeres Notenpapier mit zwölf Systemen. DIN A4-Format.'
                ],
                [
                    'notenpapier-violinschluessel.pdf',
                    'Leeres Violinschlüssel Notenpapier',
                    'PDF',
                    'Für jedermann: Leeres Notenpapier mit Violinschlüssel und zwölf Systemen. DIN A4-Format.'
                ],
                [
                    'notenpapier-violinschluessel-taktstriche.pdf',
                    'Leeres Violinschlüssel Notenpapier mit Takten',
                    'PDF',
                    'Für jedermann: Leeres Notenpapier mit Violinschlüssel und zwölf Systemen à vier Takte pro System. Perfekt für
                    Transkriptionen von Jazzsongs oder um einfach Melodien zu notieren. DIN A4-Format.'
                ],
            ]
        ]);
    }
}
