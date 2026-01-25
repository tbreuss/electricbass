<?php

namespace app\feature\alphaTab\components;

use yii\base\BaseObject;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

final class UrlRule extends BaseObject implements UrlRuleInterface
{
    /**
     * @param UrlManager $manager
     * @param string $route
     * @param array<string, mixed> $params
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'alpha-tab/editor') {
            return 'alpha-tab/editor';
        }

        if ($route === 'alpha-tab/view') {
            $url = 'play';
            if (!empty($params['uid'])) {
                $url .= '/' . $params['uid'];
            }
            return $url;
        }

        return false;
    }

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

        if ($pathInfo === 'alpha-tab/editor') {
            return ['alpha-tab/editor', []];
        }

        if (str_starts_with($pathInfo, 'play/')) {
            $uid = substr($pathInfo, 5);
            if ($uid !== '') {
                return ['alpha-tab/view', ['uid' => $uid]];
            }
        }

        return false;
    }
}
