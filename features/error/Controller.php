<?php

namespace app\features\error;

use app\components\Redirect;
use app\features\error\models\Log4xx;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\Response;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): Response|string
    {
        try {
            $requestUrl = Yii::$app->request->getUrl();
        } catch (InvalidConfigException $e) {
            $requestUrl = '__InvalidConfigException__';
        }

        $referrer = Yii::$app->request->getReferrer();

        $exception = Yii::$app->errorHandler->exception;

        if ($exception instanceof HttpException && $exception->statusCode >= 400 && $exception->statusCode < 500) {

            $trimmedRequestUrl = '/' . trim(strval(preg_replace('#/{2,}#', '/', $requestUrl)), '/');
            $redirect = Redirect::findOneByRequestUrl($trimmedRequestUrl);
            if ($redirect) {
                $redirect->updated = date('Y-m-d H:i:s');
                $redirect->count += 1;
                $redirect->save(false, ['count', 'updated']);
                return $this->redirect($redirect->to, 301);
            }

            Log4xx::create(Yii::$app->request->getMethod(), $requestUrl, $referrer, $exception->statusCode);

            return $this->render('@app/features/error/views/index', ['exception' => $exception]);
        }
        return $this->render('@app/features/error/views/index', ['exception' => $exception]);
    }
}
