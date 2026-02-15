<?php

namespace app\controllers;

use app\models\Manufacturer;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

/**
 * @deprecated
 */
final class ManufacturerController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ]
        ];
    }

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
        $encoded = json_encode($data);
        if (is_bool($encoded)) {
            return '[]';
        }
        return $encoded;
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

        Yii::$app->response->headers->add('X-Robots-Tag', 'noindex');

        return $this->redirect($manufacturer->website, 301);
    }
}
