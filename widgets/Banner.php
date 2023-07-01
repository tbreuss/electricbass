<?php

namespace app\widgets;

use yii\base\Widget;

final class Banner extends Widget
{
    /** @var string[][] */
    public array $items = [
        [
            'url' => 'https://www.amazon.de/music/unlimited?tag=electricbas03-21&linkCode=ur1',
            'img' => '@app/widgets/images/amazon_music_300x250.jpg',
            'alt' => 'Amazon Music Unlimited: Musik unbegrenzt und ohne Werbung',
        ],
        [
            'url' => 'https://www.amazon.de/ftu/plans/ANNUAL?tag=electricbas03-21&linkCode=ur1&ref_=akp_de_com_oth_acq_pd_dolo_abp',
            'img' => '@app/widgets/images/amazon_kids_300x250.jpg',
            'alt' => 'Amazon Kids: Lernen, spielen, kreativ sein',
        ],
        [
            'url' => 'https://www.amazon.de/?tag=electricbas03-21&linkCode=ur1',
            'img' => '@app/widgets/images/amazon_evergreen_300x250.jpg',
            'alt' => 'Amazon Angebote entdecken',
        ],
        [
            'url' => 'https://www.amazon.de/dp/B00NTQ6K7E?tag=electricbas03-21&linkCode=ur1',
            'img' => '@app/widgets/images/amazon_audible_300x250.jpg',
            'alt' => 'Entdecken Sie Audible Originals und exklusive Geschichten',
        ],
        [
            'url' => 'https://www.amazon.de/gp/video/primesignup?tag=electricbas03-21&linkCode=ur1',
            'img' => '@app/widgets/images/amazon_prime_300x250.gif',
            'alt' => 'Amazon prime: Unbegrenzter Film- und Seriengenuss',
        ],
        [
            'url' => 'https://www.amazon.de/gp/video/offers/?tag=electricbas03-21&linkCode=ur1&benefitId=starzplay',
            'img' => '@app/widgets/images/amazon_prime_video_300x250.jpg',
            'alt' => 'Amazon prime video: Onlinevideothek und Video-on-Demand-Angebot',
        ],
    ];

    public function run(): string
    {
        $item = $this->getRandomItem();

        $imgPath = \Yii::getAlias($item['img']);
        if ($imgPath === false) {
            return '';
        }

        $imgContent = file_get_contents($imgPath);
        if ($imgContent === false) {
            return '';
        }

        $imgType = pathinfo($imgPath, PATHINFO_EXTENSION);
        return $this->render('banner', [
            'url' => $item['url'],
            'imgAlt' => $item['alt'],
            'imgContent' => $imgContent,
            'imgType' => $imgType,
        ]);
    }

    private function getRandomItem(): array
    {
        $index = rand(0, count($this->items) - 1);
        return $this->items[$index];
    }
}
