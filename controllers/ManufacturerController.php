<?php

namespace app\controllers;

use app\models\Manufacturer;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class ManufacturerController extends Controller
{
    public function actionIndex(): string
    {
        $this->layout = 'empty';
        return $this->render('index');
    }

    public function actionData(): string
    {
        $manufacturers = Manufacturer::find()
            ->with(['country'])
            ->orderBy('name')
            ->all();

        $data = Manufacturer::allToArray($manufacturers);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode($data);
    }

    public function actionVisit(int $id): Response
    {
        $manufacturer = Manufacturer::find()
            ->where(['id' => $id])
            ->one();

        if ($manufacturer === null) {
            $message = sprintf('Hersteller mit ID %d nicht gefunden', $id);
            throw new HttpException(404, $message);
        }

        if (empty($manufacturer->website)) {
            $message = sprintf('Keine URL gefunden für Hersteller mit ID %d', $id);
            throw new HttpException(400, $message);
        }

        $manufacturer->updateCounters(['visits' => 1]);

        return $this->redirect($manufacturer->website);
    }
}
