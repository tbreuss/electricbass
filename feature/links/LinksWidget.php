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
              WHEN 'website' THEN 6
              WHEN 'magazine' THEN 7
              WHEN 'accessories' THEN 8
              WHEN 'publisher' THEN 9
              WHEN 'bassist' THEN 10
              ELSE 11
            END, title
        ";
        $links = Links::findBySql($sql)->all();

        $linksByCategory = [];
        foreach ($links as $link) {
            $linksByCategory[$this->translateCategory($link->category)][] = $link;
        }

        return $this->render('links-widget', [
            'linksByCategory' => $linksByCategory,
        ]);
    }

    private function translateCategory(string $category): string
    {
        return match ($category) {
            'instrument', 'luthier' => Yii::t('app', 'E-Bässe & Kontrabässe'),
            'website' => Yii::t('app', 'Websites'),
            'amplifier/speaker' => Yii::t('app', 'Verstärker & Boxen'),
            'magazine' => Yii::t('app', 'Magazin'),
            'accessories' => Yii::t('app', 'Zubehör'),
            'pickup' => Yii::t('app', 'Tonabnehmer'),
            'strings' => Yii::t('app', 'Saiten'),
            'publisher' => Yii::t('app', 'Verlage'),
            'bassist' => Yii::t('app', 'Berühmte Bassisten'),
            default => $category,
        };
    }
}
