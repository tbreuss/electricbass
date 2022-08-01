<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Log404;
use app\models\Rating;
use app\models\Redirect;
use app\models\Website;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use app\models\ContactForm;
use app\models\Search;
use yii\web\Response;

final class SiteController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter',
                'except' => ['captcha'],
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,
            ]
        ];
    }

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
                /* @phpstan-ignore-next-line */
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest(): string
    {
        if (Yii::$app->request->headers->has('HX-Request')) {
            return $this->renderPartial('test');
        }
        return $this->render('test');
    }

    public function actionMustache(): string
    {
        return $this->render('mustache.mustache', [
            'comments' => Comment::findLatestComments(5),
            'ratings' => Rating::findLatestRatings(5),
        ]);
    }

    public function actionIndex(): string
    {
        $count = Search::find()->where([])->count();

        $contexts = [
            'video' => 6,
            'blog' => 3,
            'album' => 6,
            'lesson' => 3,
            'lehrbuch' => 4,
            'buch' => 4
        ];

        $latests = Search::findLatestGroupedBy($contexts);
        $latestWebsites = Website::findLatest(6);

        $latestComments = Comment::findLatestComments(5);
        $latestRatings = Rating::findLatestRatings(5);

        return $this->render('index', [
            'count' => $count,
            'latests' => $latests,
            'latestComments' => $latestComments,
            'latestRatings' => $latestRatings,
            'latestVideos' => $latests['video'] ?? [],
            'latestBlogs' => $latests['blog'] ?? [],
            'latestAlbums' => $latests['album'] ?? [],
            'latestLessons' => $latests['lesson'] ?? [],
            'latestLehrbuecher' => $latests['lehrbuch'] ?? [],
            'latestBuecher' => $latests['buch'] ?? [],
            'latestWebsites' => $latestWebsites,
        ]);
    }

    public function actionContact(): Response|string
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
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionImpressum(): string
    {
        $this->layout = 'onecol';
        return $this->render('impressum');
    }

    public function actionFeed(): Response
    {
        return $this->redirect(['feed/rss'], 301);
    }

    public function actionError(): Response|string
    {
        try {
            $requestUrl = Yii::$app->request->getUrl();
        } catch (InvalidConfigException $e) {
            $requestUrl = '__InvalidConfigException__';
        }
        try {
            $pathInfo = Yii::$app->request->getPathInfo();
        } catch (InvalidConfigException $e) {
            $pathInfo = '__InvalidConfigException__';
        }
        $referrer = Yii::$app->request->getReferrer();

        $exception = Yii::$app->errorHandler->exception;
        if ($exception instanceof \yii\web\NotFoundHttpException) {
            // - www.electricbass.ch/12994
            // - www.electricbass.ch/links/le-fay-1525

            if (is_numeric($pathInfo)) {
                // 2327
                #if ($pathInfo <= 2327) {
                #    $pathInfo += 10000;
                #}
                $model = Search::find()->where(['id' => $pathInfo])->one();
                if ($model) {
                    return $this->redirect($model->url, 301)->send();
                }
            }

            // basierend auf context und id
            $id = Yii::$app->db->createCommand('SELECT id FROM oldurl WHERE url=:url')
                ->bindValue(':url', $pathInfo)
                ->queryScalar();

            if ($id > 0) {
                $model = Search::find()->where(['id' => $id])->one();
                if ($model) {
                    return $this->redirect($model->url, 301)->send();
                }
            }

            // basierend auf url
            $trimmedRequestUrl = '/' . trim(strval(preg_replace('#/{2,}#', '/', $requestUrl)), '/');
            $redirect = Redirect::findOneByRequestUrl($trimmedRequestUrl);
            if ($redirect) {
                $redirect->updated = date('Y-m-d H:i:s');
                $redirect->count += 1;
                $redirect->save(false, ['count', 'updated']);
                return $this->redirect($redirect->to, 301)->send();
            }

            Log404::log404Error($requestUrl, $referrer, date('Y-m-d H:i:s'));

            return $this->render('error', ['exception' => $exception]);
        }
        return $this->render('error', ['exception' => $exception]);
    }
}
