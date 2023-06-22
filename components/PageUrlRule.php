<?php

namespace app\components;

use yii\base\BaseObject;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

final class PageUrlRule extends BaseObject implements UrlRuleInterface
{
    /**
     * @param UrlManager $manager
     * @param Request $request
     * @return array|bool
     * @phpstan-return array<int, array<string, string>|string>|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if ($pathInfo !== '') {
            return ['site/page', ['url' => '/' . $pathInfo]];
        }
        return false;  // this rule does not apply
    }

    /**
     * @param UrlManager $manager
     * @param string $route
     * @param array<string, mixed> $params
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'site/page') {
            if (!empty($params['url'])) {
                return ltrim($params['url'], '/');
            }
        }
        return false;  // this rule does not apply
    }
}
