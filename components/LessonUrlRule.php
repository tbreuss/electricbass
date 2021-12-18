<?php

namespace app\components;

use yii\base\BaseObject;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

final class LessonUrlRule extends BaseObject implements UrlRuleInterface
{
    /**
     * @param UrlManager $manager
     * @param string $route
     * @param array<string, mixed> $params
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'lesson/index') {
            $url = 'lektionen';
            if (!empty($params['path'])) {
                $url .= '/' . $params['path'];
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
        $path = null;
        if ($pathInfo == 'lektionen') {
            $path = '';
        } elseif (strpos($pathInfo, 'lektionen/') === 0) {
            $path = substr($pathInfo, 10);
        }
        if (!is_null($path)) {
            return ['lesson/index', ['path' => $path]];
        }
        return false;  // this rule does not apply
    }
}
