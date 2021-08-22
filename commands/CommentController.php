<?php

namespace app\commands;

use app\models\Comment;
use yii\console\Controller;

final class CommentController extends Controller
{
    /**
     * Syncronizes comments in table comment with specific tables.
     */
    public function actionSync(): void
    {
        $count = Comment::synchronizeComments();
        echo $count . ' Eintraege aktualisiert' . PHP_EOL;
    }
}
