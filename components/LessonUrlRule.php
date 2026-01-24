<?php

namespace app\components;

use app\models\Lesson;
use app\models\Page;
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

        if ($pathInfo === '') {
            return false;
        }

        $lesson = Lesson::findByUrl('/' . $pathInfo);

        if (is_null($lesson)) {
            return false;
        }

        $preview = $request->getQueryParam('preview');

        if (empty($lesson->deleted) || !empty($preview)) {
            return ['lesson/index', ['path' => $lesson->url]];
        }

        return false;
    }
}
