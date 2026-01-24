<?php

namespace app\controllers;

use app\widgets\Rating;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;
use yii\web\GoneHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\ServerErrorHttpException;

final class ApiController extends Controller
{
    /**
     * @throws GoneHttpException
     */
    public function actionHits(): void
    {
        throw new GoneHttpException();
    }

    /**
     * @phpstan-return array{"ratingCount": int, "ratingAverage": string, "yourRating": string}
     * @throws MethodNotAllowedHttpException
     * @throws ServerErrorHttpException
     */
    public function actionRate(): array
    {
        Yii::$app->response->headers->add('X-Robots-Tag', 'noindex');

        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        try {
            $json = json_decode($request->getRawBody(), true);
            $widget = new Rating();
            $widget->tableName = $json['tableName'];
            $widget->tableId = $json['tableId'];
            $widget->ratingValue = $json['ratingValue'];
            return $widget->saveRating();
        } catch (InvalidConfigException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        } catch (Throwable $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}
