<?php

namespace app\controllers;

use app\feature\search\models\Search;
use app\models\Log4xx;
use app\models\Redirect;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\GoneHttpException;
use yii\web\HttpException;
use yii\web\Response;

final class SiteController extends Controller
{
    public function actionTest(): string
    {
        if (!YII_ENV_DEV) {
            throw new GoneHttpException();
        }

        if (Yii::$app->request->headers->has('HX-Request')) {
            return $this->renderPartial('test');
        }

        return $this->render('test');
    }

    public function actionFeed(): Response
    {
        return $this->redirect(['feed/rss'], 301);
    }

    public function actionError(): Response|string
    {
        try {
            $requestUrl = Yii::$app->request->getUrl();
        } catch (InvalidConfigException $e) {
            $requestUrl = '__InvalidConfigException__';
        }
        try {
            $pathInfo = Yii::$app->request->getPathInfo();
        } catch (InvalidConfigException $e) {
            $pathInfo = '__InvalidConfigException__';
        }
        $referrer = Yii::$app->request->getReferrer();

        $exception = Yii::$app->errorHandler->exception;

        if ($exception instanceof HttpException && $exception->statusCode >= 400 && $exception->statusCode < 500) {
            // - www.electricbass.ch/12994
            // - www.electricbass.ch/links/le-fay-1525

            if (is_numeric($pathInfo)) {
                // 2327
                #if ($pathInfo <= 2327) {
                #    $pathInfo += 10000;
                #}
                $model = Search::find()->where(['id' => $pathInfo])->one();
                if ($model) {
                    return $this->redirect($model->url, 301);
                }
            }

            // basierend auf context und id
            /*
            $id = Yii::$app->db->createCommand('SELECT id FROM oldurl WHERE url=:url')
                ->bindValue(':url', $pathInfo)
                ->queryScalar();

            if ($id > 0) {
                $model = Search::find()->where(['id' => $id])->one();
                if ($model) {
                    return $this->redirect($model->url, 301);
                }
            }
            */

            // basierend auf url
            $trimmedRequestUrl = '/' . trim(strval(preg_replace('#/{2,}#', '/', $requestUrl)), '/');
            $redirect = Redirect::findOneByRequestUrl($trimmedRequestUrl);
            if ($redirect) {
                $redirect->updated = date('Y-m-d H:i:s');
                $redirect->count += 1;
                $redirect->save(false, ['count', 'updated']);
                return $this->redirect($redirect->to, 301);
            }

            Log4xx::create(Yii::$app->request->getMethod(), $requestUrl, $referrer, $exception->statusCode);

            return $this->render('error', ['exception' => $exception]);
        }
        return $this->render('error', ['exception' => $exception]);
    }
}
