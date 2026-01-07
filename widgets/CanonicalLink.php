<?php

namespace app\widgets;

use app\helpers\Url;
use Yii;
use yii\base\Widget;

class CanonicalLink extends Widget
{
    public bool $isPaginated = false;

    /**
     * @var string[]
     */
    public array $keepParams = [];

    public function run(): void
    {
        $url = [Yii::$app->controller->getRoute()];
        $queryParams = Yii::$app->request->getQueryParams();

        foreach ($this->keepParams as $keepParam) {
            if (array_key_exists($keepParam, $queryParams)) {
                $url[$keepParam] = $queryParams[$keepParam];
            }
        }

        if ($this->isPaginated && array_key_exists('page', $queryParams) && ($queryParams['page'] > 1)) {
            $url['page'] = $queryParams['page'];
        }

        if (Yii::$app->request->url !== Url::to($url)) {
            Yii::$app->view->registerLinkTag([
                'rel' => 'canonical',
                'href' => Url::to($url, true),
            ]);
        }
    }
}
