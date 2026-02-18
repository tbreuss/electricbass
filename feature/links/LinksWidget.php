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
            $linksByCategory[$this->translateCategory($link->category)][] = $link;
        }

        return $this->render('links-widget', [
            'linksByCategory' => $linksByCategory,
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
}
