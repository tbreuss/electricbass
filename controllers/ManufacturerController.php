<?php

namespace app\controllers;

use app\helpers\Url;
use app\models\Manufacturer;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class ManufacturerController extends Controller
{
    public function actionIndex()
    {
        $manufacturers = Manufacturer::find()
            ->asArray()
            ->orderBy('name')
            ->all();

        array_walk($manufacturers, function(array &$m) {
            $m['visitUrl'] = strlen($m['website']) === 0 ? '' : Url::to(['visit', 'id' => $m['id']]);
        });

        return $this->render('index', [
            'manufacturers' => $manufacturers
        ]);
    }

    public function actionData()
    {
        $manufacturers = Manufacturer::find()
            ->with(['country'])
            ->orderBy('name')
            ->all();

        /*array_walk($manufacturers, function(Manufacturer $m) {
            $m->visitUrl = strlen($m->website) === 0 ? '' : Url::to(['visit', 'id' => $m->id]);
        });*/

        $data = Manufacturer::allToArray($manufacturers);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode($data);
    }

    public function actionVisit(int $id)
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
