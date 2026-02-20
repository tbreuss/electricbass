<?php

namespace app\feature\links;

use app\feature\links\models\Links;
use Yii;
use yii\base\Widget;

final class LinksWidget extends Widget
{
    /**
     * @return string|void
     */
    public function run()
    {
        $sql = "
            SELECT
                category,
                website,
                title,
                countryCode
            FROM `website`
            WHERE (`archived` IS NULL)
              AND (`deleted` IS NULL)
            ORDER BY CASE category
              WHEN 'instrument' THEN 1
              WHEN 'luthier' THEN 1
              WHEN 'pickup' THEN 2
              WHEN 'strings' THEN 3
              WHEN 'amplifier/speaker' THEN 4
              WHEN 'accessories' THEN 5
              WHEN 'website' THEN 6
              WHEN 'magazine' THEN 7
              WHEN 'publisher' THEN 8
              WHEN 'bassist' THEN 9
              ELSE 10
            END, title
        ";
        $links = Links::findBySql($sql)->all();

        $linksByCategory = [];
        foreach ($links as $link) {
            $category = $this->translateCategory($link->category);
            $subtitle = $this->translateSubtitle($link->category);
            if (!isset($linksByCategory[$category])) {
                $linksByCategory[$category] = [
                    'title' => $category,
                    'subtitle' => $subtitle,
                    'links' => [],
                ];
            }
            $linksByCategory[$category]['links'][] = $link;
        }

        return $this->render('links-widget', [
            'linkItems' => array_values($linksByCategory),
        ]);
    }

    private function translateCategory(string $category): string
    {
        return match ($category) {
            'accessories' => Yii::t('app', 'Zubehör'),
            'amplifier/speaker' => Yii::t('app', 'Audio / Verstärker / Boxen'),
            'bassist' => Yii::t('app', 'Bassisten / Bands'),
            'instrument', 'luthier' => Yii::t('app', 'E-Bässe / Akustikbässe'),
            'magazine' => Yii::t('app', 'Magazine'),
            'pickup' => Yii::t('app', 'Tonabnehmer'),
            'publisher' => Yii::t('app', 'Verlage'),
            'strings' => Yii::t('app', 'Saiten'),
            'website' => Yii::t('app', 'Blogs / Websites'),
            default => $category,
        };
    }

    private function translateSubtitle(string $category): string
    {
        return match ($category) {
            'accessories' => Yii::t('app', 'Links zu Herstellern von E-Bass Zubehör.'),
            'amplifier/speaker' => Yii::t('app', 'Linksammlung zu Herstellern von Verstärkern, Lautsprechern, Effektgeräten und Audioprodukten für E-Bass.'),
            'bassist' => Yii::t('app', 'Linksammlung mit Websites berühmter Bassisten und basslastiger Bands.'),
            'instrument', 'luthier' => Yii::t('app', 'Links zu Herstellern von E-Bässen, Akustischen Bassgitarren und E-Kontrabässen.'),
            'magazine' => Yii::t('app', 'Links zu Online-Magazinen'),
            'pickup' => Yii::t('app', 'Linksammlung zu Herstellern von Tonabnehmern für E-Bässe.'),
            'publisher' => Yii::t('app', 'Links zu Verlagen mit Noten für E-Bass.'),
            'strings' => Yii::t('app', 'Links zu Herstellern von Saiten für E-Bässe.'),
            'website' => Yii::t('app', 'Links zu Blogs, Foren und Websites zum Thema E-Bass.'),
            default => $category,
        };
    }
}
