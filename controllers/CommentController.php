<?php

namespace app\controllers;

use app\helpers\Url;
use app\models\Comment;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class CommentController extends Controller
{
    public function actionIndex(string $name, int $id): string
    {
        $request = Yii::$app->request;
        $queryParams = array_diff_key($request->getQueryParams(), ['id' => 0, 'name' => '']);
        $referrer = $request->getReferrer();
        $hostInfo = $request->getHostInfo();

        if (!empty($queryParams)) {
            throw new NotFoundHttpException();
        }

        if (is_string($referrer) && is_string($hostInfo) && !str_starts_with($referrer, $hostInfo)) {
            throw new NotFoundHttpException();
        }

        [$title, $url] = $this->fetchTitleAndUrlFromParentTable($name, $id);

        $model = new Comment();
        $model->tableName = $name;
        $model->tableId = $id;

        $count = Comment::find()
            ->where('active = 1 AND deleted = 0 AND tableName = :tableName AND tableId = :tableId', [':tableName' => $name, ':tableId' => $id])
            ->count();

        if ($request->isPost) {
            $session = Yii::$app->session;
            if (!$model->load($request->post())) {
                $error = 'Beim Laden der Formulardaten ist ein Fehler aufgetreten.';
                $session->setFlash('comment/error', $error);
            } elseif (!$model->validate()) {
                $error = 'Beim Validieren der Formulardaten ist ein Fehler aufgetreten.';
                $session->setFlash('comment/error', $error);
            } else {
                if (empty($model->nspm)) {
                    $model->email = '';
                    $model->website = '';
                    $model->ipAddress = $_SERVER['REMOTE_ADDR'];
                    $model->active = 1;
                    $model->save(false);

                    $this->updateCounterOnParentTable($name, $id);
                    $this->sendMailToAdmin($model);
                } else {
                    Yii::warning('Comments form - spam attack');
                }
                $success = 'Vielen Dank! Wir haben deinen Kommentar freigeschaltet.';
                $session->setFlash('comment/success', $success);
            }
        }

        return $this->render('index', [
            'model' => $model,
            'count' => $count,
            'url' => $url,
            'title' => $title,
        ]);
    }

    public function actionRules(): string
    {
        return $this->render('rules');
    }

    protected function sendMailToAdmin(Comment $model): bool
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
    protected function updateCounterOnParentTable(string $table, int $id): int
    {
        $sql = sprintf('UPDATE {{%s}} SET comments = comments + 1 WHERE id=:id', $table);
        return Yii::$app->db->createCommand($sql)
            ->bindValue(':id', $id)
            ->execute();
    }

    private function fetchTitleAndUrlFromParentTable(string $table, int $id): array
    {
        if (!in_array($table, ['advertisement', 'album', 'blog', 'catalog', 'fingering', 'glossar', 'joke', 'lesson', 'page', 'quote', 'video', 'website'])) {
            throw new BadRequestHttpException();
        }

        if ($id < 1) {
            return match ($table) {
                'joke' => ['Bassistenwitze', Url::to(['/joke/index'])],
                'quote' => ['Zitate von Bassisten', Url::to(['/quote/index'])],
                default => throw new BadRequestHttpException(),
            };
        }

        $sql = sprintf('SELECT url, title FROM {{%s}} WHERE id=:id', $table);
        $row = Yii::$app->db->createCommand($sql, ['id' => $id])->queryOne();

        if (is_array($row)) {
            return [$row['title'], $row['url']];
        }

        throw new NotFoundHttpException();
    }
}
