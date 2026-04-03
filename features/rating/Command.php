<?php

namespace app\features\rating;

use app\features\rating\models\Rating;
use yii\console\Controller;

final class Command extends Controller
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
