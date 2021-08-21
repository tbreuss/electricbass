<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $code
 * @property string $requestUrl
 * @property string $created
 * @property string $referrer
 * @property int $counter
 * @property string $modified
 */
class Log404 extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{log_404}}';
    }

    public static function log404Error(string $pathInfo, ?string $referrer, string $now): bool
    {
        $model = Log404::find()
            ->where(['requestUrl' => $pathInfo])
            ->one();

        if ($model === null) {
            $model = new Log404();
            $model->code = 404;
            $model->requestUrl = $pathInfo;
            $model->created = $now;
        }

        $referrers = [];
        if (strlen($model->referrer) > 0) {
            $referrers = explode(';', $model->referrer);
        }
        if (strlen($referrer) > 0) {
            $referrers[] = $referrer;
        }

        $model->referrer = join(';', array_unique(array_filter($referrers)));
        $model->counter += 1;
        $model->modified = $now;

        return $model->save(false);
    }
}
