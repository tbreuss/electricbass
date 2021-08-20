<?php

namespace app\widgets;

use app\models\Comment;
use Yii;
use yii\base\Widget;

class Comments extends Widget
{
    public $tableName;
    public $tableId;
    public $models;

    public function init()
    {
        $this->models = Comment::find()
            ->where('active = 1 AND deleted = 0 AND tableName = :tableName AND tableId = :tableId', [':tableName' => $this->tableName, ':tableId' => $this->tableId])
            ->orderBy('created ASC')
            ->all();
        parent::init();
    }

    public function run()
    {
        return $this->render('comments/main', [
            'list' => $this->renderComments(),
            'form' => $this->renderForm(),
        ]);
    }

    protected function renderComments()
    {
        return $this->render('comments/list', [
            'models' => $this->models,
        ]);
    }

    protected function renderForm()
    {
        $request = \Yii::$app->request;

        $model = new Comment();

        if ($request->isPost) {
            $session = \Yii::$app->session;
            if (!$model->load($request->post())) {
                $error = 'Beim Laden der Formulardaten ist ein Fehler aufgetreten.';
                $session->setFlash('comment/error', $error);
            } elseif (!$model->validate()) {
                $error = 'Beim Validieren der Formulardaten ist ein Fehler aufgetreten.';
                $session->setFlash('comment/error', $error);
            } else {
                if (empty($model->nspm)) {
                    $model->tableName = $this->tableName;
                    $model->tableId = $this->tableId;
                    $model->email = '';
                    $model->website = '';
                    $model->ipAddress = $_SERVER['REMOTE_ADDR'];
                    $model->active = 1;
                    $model->save(false);

                    $this->updateCounterOnParentTable($this->tableName, $this->tableId);
                    $this->sendMailToAdmin($model);
                } else {
                    Yii::warning('Comments form - spam attack');
                }
                $success = 'Vielen Dank! Wir haben deinen Kommentar freigeschaltet.';
                $session->setFlash('comment/success', $success);
                $this->refresh('#comments');
            }
        }

        return $this->render('comments/form', [
            'model' => $model,
            'count' => count($this->models)
        ]);
    }

    /**
     * @param string $anchor
     */
    protected function refresh($anchor = '')
    {
        $url = Yii::$app->getRequest()->getUrl() . $anchor;
        Yii::$app->getResponse()->redirect($url)->send();
        exit;
    }

    /**
     * Sends an email to the admin.
     * @return boolean whether the model passes validation
     */
    protected function sendMailToAdmin($model)
    {
        $textBody = $model->text;
        $textBody .= "\n\nName: $model->name";
        $textBody .= "\nE-Mail: $model->email";
        if (!empty($model->website)) {
            $textBody .= "\nWebsite: $model->website";
        }
        $textBody .= "\nIP-Adresse: " . $_SERVER['REMOTE_ADDR'];
        return Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setSubject('Neuer Kommentar | electricbass.ch')
            ->setTextBody($textBody)
            ->send();
    }

    /**
     * @param string $table
     * @param int $id
     * @return int
     * @throws \yii\db\Exception
     */
    protected function updateCounterOnParentTable($table, $id)
    {
        $sql = sprintf('UPDATE {{%s}} SET comments = comments + 1 WHERE id=:id', $table);
        $n = Yii::$app->db->createCommand($sql)
            ->bindValue(':id', $id)
            ->execute();
        return $n;
    }

}
