<?php

namespace app\feature\contact;

use app\feature\contact\models\ContactForm;
use Yii;
use yii\web\Response;

final class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     * @phpstan-return array<string, array<string, mixed>>
     */
    public function actions(): array
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'offset' => 0,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(): Response|string
    {
        $model = new ContactForm();
        if (Yii::$app->request->isPost) {
            if (!$model->load(Yii::$app->request->post())) {
                $error = 'Beim Laden der Formulardaten ist ein Fehler aufgetreten.';
            } elseif (!$model->validate()) {
                $error = 'Beim Validieren der Formulardaten ist ein Fehler aufgetreten.';
            } elseif (!$model->contact(Yii::$app->params['adminEmail'])) {
                $error = 'Beim Versenden der Nachricht via E-Mail ist ein Fehler aufgetreten.';
            } else {
                $success = 'Danke für deine Nachricht. Ich melde mich möglichst rasch bei dir.';
                Yii::$app->session->setFlash('contact/success', $success);
                return $this->refresh();
            }
            // TODO Fehler protokollieren
            Yii::$app->session->setFlash('contact/error', $error);
        }
        $this->layout = 'onecol';
        return $this->render('@app/feature/contact/views/index', [
            'model' => $model,
        ]);
    }
}
