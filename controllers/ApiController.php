<?php

namespace app\controllers;

use app\widgets\Hits;
use app\widgets\Rating;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

class ApiController extends Controller
{

    /**
     * @return bool
     */
    public function actionHits()
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
     * @return array
     */
    public function actionRate()
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
        } catch (Exception $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}
