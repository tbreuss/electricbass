<?php

namespace app\controllers;

use app\widgets\Hits;
use app\widgets\Rating;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

final class ApiController extends Controller
{

    public function actionHits(): bool
    {
        if (!Yii::$app->request->isPost) {
            return false;
        }

        $json = json_decode(Yii::$app->request->getRawBody(), true);

        $tableName = trim($json['tableName'] ?? '');
        $tableId = intval($json['tableId'] ?? 0);

        $widget = new Hits();
        $widget->tableName = $tableName;
        $widget->tableId = $tableId;

        return $widget->increaseHits();
    }

    /**
     * @phpstan-return array{"ratingCount": int, "ratingAverage": string, "yourRating": string}
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function actionRate(): array
    {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new HttpException(400);
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
