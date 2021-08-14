<?php

namespace app\controllers;

use app\helpers\Div;
use app\models\Advertisement;
use app\models\AdvertisementContactForm;
use app\models\AdvertisementEmailForm;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class AdvertisementController
 * @package app\controllers
 */
class AdvertisementController extends Controller
{
    /**
     * Index Action
     * @param int $page
     * @return string
     */
    public function actionIndex($page = 0)
    {
        $models = Advertisement::findAllForAdvertisementIndexController();
        return $this->render('index', array(
            'models' => $models,
            'page' => $page
        ));
    }

    /**
     * Contact Action
     * @param int $id
     * @return string
     */
    public function actionContact($id)
    {
        $advertisement = Advertisement::findById($id, true);
        $model = new AdvertisementContactForm;

        if (isset($_POST['AdvertisementContactForm'])) {
            $model->attributes = $_POST['AdvertisementContactForm'];
            if ($model->validate()) {

                if (empty($model->nspm)) {
                    $this->sendContactMail($model, $advertisement);
                    $advertisement->updateCounters(array('requests' => 1));
                } else {
                    $this->sendAdminMail('Spamversuch Kleinanzeigen Kontakt', print_r($_POST, true));
                }

                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->redirect($advertisement->createDetailUrl(true));
            }
        }

        return $this->render('contact', array(
            'model' => $model,
            'advertisement' => $advertisement
        ));

    }

    /**
     * View Action
     * @param int|string $id
     * @return string
     */
    public function actionView($id)
    {
        $model = Advertisement::findById('/kleinanzeigen/' . $id, false);

        // Umleitung bei Aufruf über ID, falls URL-Segment definiert ist
        if (is_numeric($id) && !empty($model->url)) {
            return $this->redirect($model->createDetailUrl(true), 301);
        }

        #$model->increaseHits();

        return $this->render('view', array(
            'model' => $model
        ));
    }

    /**
     * Add Action
     * @return string
     */
    public function actionAdd()
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
                return $this->redirect(array('index'));
            }
        }
        return $this->render('add', array('model' => $model));
    }

    /**
     * @param int $id
     * @param string $accessCode
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionActivate($id, $accessCode)
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model) || empty($accessCode) || ($accessCode != Div::createAccessCode($id))) {
            throw new NotFoundHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        $model->activate();
        return $this->render('renew', array('model' => $model));
    }

    /**
     * @param int $id
     * @param string $accessCode
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRenew($id, $accessCode)
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model) || empty($accessCode) || ($accessCode != Div::createAccessCode($id))) {
            throw new NotFoundHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        $model->renew();
        $model->updateCounters(array('renewals' => 1));
        return $this->render('renew', array('model' => $model));
    }

    /**
     * @param int $id
     * @param string $accessCode
     * @param int $confirmed
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDelete($id, $accessCode, $confirmed = 0)
    {
        $model = Advertisement::findById($id, true);
        if (is_null($model) || empty($accessCode) || ($accessCode != Div::createAccessCode($id))) {
            throw new NotFoundHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
        }
        if (!empty($confirmed)) {
            $model->delete();
            Yii::$app->session->setFlash('deleteConfirmed');
            return $this->redirect(array('index'));
        }
        return $this->render('delete', array('model' => $model, 'confirmed' => $confirmed));
    }

    /**
     * @param int $id
     * @param string $accessCode
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $accessCode)
    {
        $model = Advertisement::findById($id, false);
        if (is_null($model) || empty($accessCode) || ($accessCode != Div::createAccessCode($id))) {
            throw new NotFoundHttpException('Die Seite wurde mit ungültigen Parametern aufgerufen.');
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
        return $this->render('update', array('model' => $model));
    }

    /**
     * @return string
     */
    public function actionManage()
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
        return $this->render('manage', [
            'model' => $model
        ]);
    }

    /**
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $textBody
     * @return bool
     */
    protected function sendMail($to, $from, $subject, $textBody): bool
    {
        return Yii::$app->mailer
            ->compose()
            ->setFrom($to)
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($textBody)
            ->send();
    }

    /**
     * @param string $subject
     * @param string $textBody
     * @return bool
     */
    protected function sendAdminMail($subject, $textBody)
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

    protected function sendConfirmationMail($model)
    {
        $models = Advertisement::findAllByEmail($model->email);

        $message = Yii::$app->mailer->compose([
            'html' => 'advertisement/confirmation_html',
            'text' => 'advertisement/confirmation_text',
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

    /**
     * @param AdvertisementContactForm $model
     * @param Advertisement $advertisement
     * @return bool
     */
    protected function sendContactMail($model, $advertisement)
    {
        $message = Yii::$app->mailer->compose([
            'html' => 'advertisement/contact_html',
            'text' => 'advertisement/contact_text',
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
     * @param array $models
     * @param string $email
     * @return bool
     */
    protected function sendManageMail(array $models, $email)
    {
        $message = Yii::$app->mailer->compose([
            'html' => 'advertisement/manage_html',
            'text' => 'advertisement/manage_text',
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