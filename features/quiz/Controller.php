<?php

namespace app\features\quiz;

use app\features\quiz\models\Quiz;
use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public $layout = '@app/features/quiz/views/layout';

    public function actionView(string $uid): string
    {
        $model = Quiz::findOneByUid($uid);

        if ($model === null) {
            throw new GoneHttpException();
        }

        return $this->render('@app/features/quiz/views/view', ['model' => $model]);
    }
}
