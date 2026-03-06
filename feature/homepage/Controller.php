<?php

namespace app\feature\homepage;

use app\feature\comment\models\Comment;
use app\feature\rating\models\Rating;
use app\feature\search\models\Search;

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

        return $this->render('@app/feature/homepage/views/index', [
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
