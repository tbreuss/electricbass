<?php

namespace app\helpers;

use Yii;

final class Url extends \yii\helpers\Url
{
    /**
     * @param array<array>|array<int, string> $route
     */
    public static function rememberReferrer(array $route, string $name): void
    {
        $referrer = Yii::$app->request->referrer ?? '';
        [$referrerPath] = explode('?', $referrer);
        $url = Url::to($route, true);
        if ($url === $referrerPath) {
            self::remember($referrer, $name);
        } else {
            self::remember($route, $name);
        }
    }

    /**
     * @param string $asin
     * @return string
     */
    public static function toAmazonProduct(string $asin): string
    {
        # https://www.amazon.de/dp/%s?tag=%s&linkCode=ogi&th=1&psc=1 (alter LInk)

        $link = strtr(
            'https://www.amazon.de/dp/{asin}/ref=nosim?tag={tag}',
            [
                '{asin}' => $asin,
                '{tag}' => $_ENV['AMAZON_PAAPI5_PARTNER_TAG'] ?? '',
            ]
        );
        return $link;
    }
}
