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
        $referrer = Yii::$app->request->referrer;
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
        return sprintf(
            'https://www.amazon.de/dp/%s?tag=%s&linkCode=ogi&th=1&psc=1',
            $asin,
            $_ENV['AMAZON_PAAPI5_PARTNER_TAG']
        );
    }

}
