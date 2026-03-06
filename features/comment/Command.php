<?php

namespace app\features\comment;

use app\features\comment\models\Comment;
use yii\console\Controller;

final class Command extends Controller
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
