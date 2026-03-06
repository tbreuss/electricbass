<?php

namespace app\features\homepage;

use app\features\comment\models\Comment;
use app\features\rating\models\Rating;
use app\features\search\models\Search;

final class Controller extends \yii\web\Controller
{
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
        $latestComments = Comment::findLatestComments(5);
        $latestRatings = Rating::findLatestRatings(5);

        return $this->render('@app/features/homepage/views/index', [
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
        ]);
    }
}
