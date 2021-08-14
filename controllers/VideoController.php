<?php

namespace app\controllers;

use app\helpers\Div;
use app\helpers\Url;
use app\models\Blog;
use app\models\Video;
use SimpleXMLElement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class VideoController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['sort']
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        //$this->migrate();
        //$this->tags();
        $provider = Video::getActiveDataProvider();
        return $this->render('index', [
            'dataProvider' => $provider,
            'videos' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }

    /**
     * @param string $eid
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(string $eid)
    {
        $video = Video::findOneOrNull('/videos/' . $eid);

        if (is_null($video)) {
            throw new NotFoundHttpException();
        }

        $similarVideos = Video::findSimilars($video->id, $video->getTagsAsArray(), 9);
        if (empty($similarVideos)) {
            $similarVideos = Video::findLatest($video->id);
        }

        Url::rememberReferrer(['video/index'], 'video');

        return $this->render('view', [
            'video' => $video,
            'similarVideos' => $similarVideos
        ]);
    }

    private function tags()
    {
        $tags = [];
        $videos = Video::find()
            ->where(['deleted' => null])
            ->all();
        foreach ($videos as $video) {
            $tags = array_merge($tags, explode(',', $video->tags));
        }

        $tags = array_unique($tags);
        $tags = array_map(function($t) {
            return ['name' => trim($t)];
        }, $tags);

        array_walk($tags, function ($t) {
            \Yii::$app->db->createCommand()
                ->insert('video_tag', $t)
                ->execute();
        });
    }

    private function randomId(int $length): string
    {
        #$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    private function migrate()
    {
        $blogs = Blog::find()
            ->where(['deleted' => null, 'categoryId' => 7])
            ->andWhere(['like', 'text', '%[youtube%', false])
            ->orderBy('id')
            ->all();

        foreach ($blogs as $blog) {
            $pattern = '/\[youtube(.+?)?\](?:(.+?)?\[\/youtube\])?/';
            $found = preg_match($pattern, $blog->text, $matches);

            if (empty($found)) {
                continue;
            }

            $eid = $this->randomId(8);
            $urlSegment = Div::normalizeUrlSegment($blog->title) . '-' . $eid;

            $x = new SimpleXMLElement("<element {$matches[1]} />");

            $column = [
                'eid' => $eid,
                'oid' => $blog->id,
                'countryCode' => $blog->countryCode,
                'language' => $blog->language,
                'platform' => 'youtube',
                'key' => $x->attributes()->key,
                'width' => (int)$x->attributes()->width,
                'height' => (int)$x->attributes()->height,
                'title' => $blog->title,
                'urlSegment' => $urlSegment,
                'abstract' => $blog->abstract,
                'text' => trim(str_replace($matches[0], '', $blog->text)),
                'tags' => $blog->tags,
                //'canonical' => '/blog/' . $blog->urlSegment,
                'comments' => $blog->comments,
                'ratings' => $blog->ratings,
                'ratingAvg' => $blog->ratingAvg,
                'hits' => $blog->hits,
                'created' => $blog->created,
                'modified' => $blog->modified
            ];

            \Yii::$app->db->createCommand()
                ->insert('video_new', $column)
                ->execute();

            $columns = [
                'from' => '/blog/' . $blog->urlSegment,
                'to' => '/videos/' . $urlSegment
            ];
            \Yii::$app->db->createCommand()
                ->insert('redirect', $columns)
                ->execute();

        }
        die('Migration finished');
    }

}