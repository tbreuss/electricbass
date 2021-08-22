<?php

namespace app\commands;

use app\models\Rating;
use yii\console\Controller;

final class RatingController extends Controller
{
    /**
     * Syncronizes ratings in table rating with specific tables.
     */
    public function actionSync(): void
    {
        $count = Rating::synchronizeRatings();
        echo $count . ' Eintraege aktualisiert' . PHP_EOL;
    }
}
