<?php

namespace app\features\advertisement;

use app\features\advertisement\models\Advertisement;
use app\features\advertisement\models\AdvertisementContactForm;
use app\features\advertisement\models\AdvertisementEmailForm;
use app\helpers\Div;
use Yii;
use yii\db\Expression;
use yii\web\GoneHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Class AdvertisementController
 * @package app\controllers
 */
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

    public function actionIndex(int $page = 0): string
    {
        $models = Advertisement::findAllForAdvertisementIndexController();
        return $this->render('@app/features/advertisement/views/index', [
            'models' => $models,
            'page' => $page
        ]);
    }

    public function actionContact(string $id): Response|string
    {
        $advertisement = Advertisement::findById($id, true);
        if (is_null($advertisement)) {
            throw new NotFoundHttpException();
        }

        $model = new AdvertisementContactForm();

        if (isset($_POST['AdvertisementContactForm'])) {
            $model->attributes = $_POST['AdvertisementContactForm'];
            if ($model->validate()) {
                if (empty($model->nspm)) {
                    $this->sendContactMail($model, $advertisement);
                    $advertisement->updateCounters(['requests' => 1]);
                } else {
                    $this->sendAdminMail('Spamversuch Kleinanzeigen Kontakt', print_r($_POST, true));
                }

                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->redirect($advertisement->createDetailUrl(true));
            }
        }

        return $this->render('@app/features/advertisement/views/contact', [
            'model' => $model,
            'advertisement' => $advertisement
        ]);
    }

    public function actionView(string $id): Response|string
    {
        $model = Advertisement::findById('/kleinanzeigen/' . $id, false);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        if (!empty($model->spam)) {
            // if the ad was spam show the default not found error
            throw new GoneHttpException();
        }

        // Umleitung bei Aufruf über ID, falls URL-Segment definiert ist
        if (is_numeric($id) && !empty($model->url)) {
            return $this->redirect($model->createDetailUrl(true), 301);
        }

        #$model->increaseHits();

        return $this->render('@app/features/advertisement/views/view', [
            'model' => $model
        ]);
    }

    public function actionAdd(): Response|string
    {
        $model = new Advertisement();
        if (isset($_POST['Advertisement'])) {
            $model->attributes = $_POST['Advertisement'];
            if ($model->validate()) {
                if (empty($model->nspm)) {
                    $model->hidden = 0; // freigeschaltet
                    $model->date = new Expression('NOW()');
                    $model->created = new Expression('NOW()');
                    $model->modified = new Expression('NOW()');
                    $model->save();

                    $model->handleUpload();

                    $this->sendConfirmationMail($model);
                }
                Yii::$app->session->setFlash('addFormSubmitted');
                return $this->redirect(['index']);
            }
        }
        return $this->render('@app/features/advertisement/views/add', ['model' => $model]);
    }

    public function actionActivate(string $id, string $accessCode): string
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        if (empty($accessCode) || ($accessCode != Div::createAccessCode($model->id))) {
            throw new UnauthorizedHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        $model->activate();
        return $this->render('@app/features/advertisement/views/renew', ['model' => $model]);
    }

    public function actionRenew(string $id, string $accessCode): string
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        if (empty($accessCode) || ($accessCode != Div::createAccessCode($model->id))) {
            throw new UnauthorizedHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        $model->renew();
        $model->updateCounters(['renewals' => 1]);
        return $this->render('@app/features/advertisement/views/renew', ['model' => $model]);
    }

    public function actionDelete(string $id, string $accessCode, int $confirmed = 0): Response|string
    {
        $model = Advertisement::findById($id, true);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        if (empty($accessCode) || ($accessCode != Div::createAccessCode($model->id))) {
            throw new UnauthorizedHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        if (!empty($confirmed)) {
            $model->softDelete();
            Yii::$app->session->setFlash('deleteConfirmed');
            return $this->redirect(['index']);
        }
        return $this->render('@app/features/advertisement/views/delete', ['model' => $model, 'confirmed' => $confirmed]);
    }

    public function actionUpdate(string $id, string $accessCode): Response|string
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        if (empty($accessCode) || ($accessCode != Div::createAccessCode($model->id))) {
            throw new UnauthorizedHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        if (isset($_POST['Advertisement'])) {
            $model->attributes = $_POST['Advertisement'];

            if (isset($_POST['delete']) && $model->unlinkPhoto()) {
                Yii::$app->session->setFlash('fotoDeleted');
                return $this->refresh('#del');
            }
            $model->modified = new Expression('NOW()');
            if (isset($_POST['save']) && $model->save()) {
                $model->renew();
                $model->handleUpload();
                Yii::$app->session->setFlash('updateFormSubmitted');
                #return $this->redirect(array('index'));
                return $this->refresh();
            }
        }
        return $this->render('@app/features/advertisement/views/update', ['model' => $model]);
    }

    public function actionManage(): Response|string
    {
        $model = new AdvertisementEmailForm();
        if (isset($_POST['AdvertisementEmailForm'])) {
            $model->attributes = $_POST['AdvertisementEmailForm'];
            if ($model->validate()) {
                if (empty($model->name)) {
                    $models = Advertisement::findAllByEmail($model->email);
                    if (count($models) > 0) {
                        $this->sendManageMail($models, $model->email);
                    }
                } else {
                    $this->sendAdminMail('electricbass.ch: Automatisierte Kleinanzeigen', implode("\n", $model->attributes));
                }
                Yii::$app->session->setFlash('manageFormSubmitted');
                return $this->refresh();
            }
        }
        return $this->render('@app/features/advertisement/views/manage', [
            'model' => $model
        ]);
    }

    protected function sendMail(string $to, string $from, string $subject, string $textBody): bool
    {
        return Yii::$app->mailer
            ->compose()
            ->setFrom($to)
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($textBody)
            ->send();
    }

    protected function sendAdminMail(string $subject, string $textBody): bool
    {
        $to = Yii::$app->params['adminEmail'];
        $from = 'noreply@electricbass.ch';
        return Yii::$app->mailer
            ->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($textBody)
            ->send();
    }

    protected function sendConfirmationMail(Advertisement $model): bool
    {
        $models = Advertisement::findAllByEmail($model->email);

        $message = Yii::$app->mailer->compose([
            'html' => '@app/features/advertisement/mail/confirmation_html',
            'text' => '@app/features/advertisement/mail/confirmation_text',
        ], [
            'models' => $models,
            'model' => $model
        ]);
        $message->setFrom(Yii::$app->params['senderEmail']);
        $message->setTo($model->email);
        // todo: set return path
        //$message->setReturnPath(Yii::app()->params['senderEmail']);
        $message->setSubject('Dein Inserat ist online | electricbass.ch');
        return $message->send();
    }

    protected function sendContactMail(AdvertisementContactForm $model, Advertisement $advertisement): bool
    {
        $message = Yii::$app->mailer->compose([
            'html' => '@app/features/advertisement/mail/contact_html',
            'text' => '@app/features/advertisement/mail/contact_text',
        ], [
            'advertisement' => $advertisement,
            'model' => $model
        ]);

        $message->setFrom([$model->email => $model->name]);
        $message->setTo($advertisement->email);
        // todo: set return path
        //$message->setReturnPath(Yii::app()->params['senderEmail']);
        $message->setSubject($advertisement->title . ' | electricbass.ch');
        return $message->send();
    }

    /**
     * @param Advertisement[] $models
     * @param string $email
     * @return bool
     */
    protected function sendManageMail(array $models, string $email): bool
    {
        $message = Yii::$app->mailer->compose([
            'html' => '@app/features/advertisement/mail/manage_html',
            'text' => '@app/features/advertisement/mail/manage_text',
        ], [
            'models' => $models,
            'email' => $email
        ]);
        $message->setFrom(Yii::$app->params['senderEmail']);
        $message->setTo($email);
        // todo: set return path
        //$message->setReturnPath(Yii::$app->params['senderEmail']);
        $message->setSubject('Deine Inserate verwalten | electricbass.ch');
        return $message->send();
    }
}
