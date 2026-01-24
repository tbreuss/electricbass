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
        if ($route === 'alpha-tab/view') {
            $url = 'play';
            if (!empty($params['uid'])) {
                $url .= '/' . $params['uid'];
            }
            return $url;
        }
        return false;  // this rule does not apply
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
        $uid = null;
        if ($pathInfo == 'play') {
            $uid = '';
        } elseif (strpos($pathInfo, 'play/') === 0) {
            $uid = substr($pathInfo, 5);
        }
        if (!is_null($uid)) {
            return ['alpha-tab/view', ['uid' => $uid]];
        }
        return false;  // this rule does not apply
    }
}
