<?php

namespace app\commands;

use app\models\Comment;
use yii\console\Controller;

class CommentController extends Controller
{
    /**
     * Syncronizes comments in table comment with specific tables.
     */
    public function actionSync()
    {
        $count = Comment::synchronizeComments();
        echo $count . ' Eintraege aktualisiert' . PHP_EOL;
    }
}
