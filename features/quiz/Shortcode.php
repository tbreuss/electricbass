<?php

namespace app\features\quiz;

use app\features\quiz\models\Quiz;
use Yii;

final class Shortcode
{
    /**
     * @phpstan-param array{"id": int, "uid": string} $options
     */
    public static function list(array $options, string $content): string
    {
        $options = array_merge([
            'uid' => '',
        ], $options);

        $models = Quiz::find()
            ->where(['deleted' => null])
            ->where('uid LIKE :uid', [':uid' => $options['uid']])
            ->all();

        return Yii::$app->controller->renderPartial('@app/features/quiz/views/list-shortcode', [
            'models' => $models,
        ]);
    }
}
