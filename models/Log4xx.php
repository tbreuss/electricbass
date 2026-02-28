<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property string $method
 * @property string $url
 * @property string $referrer
 * @property int $code
 * @property string $created
 * @property int $counter
 * @property string $modified
 */
final class Log4xx extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{log_4xx}}';
    }

    public static function create(string $method, string $url, ?string $referrer, int $statusCode = 0): bool
    {
        $model = Log4xx::find()
            ->where(['method' => $method, 'url' => $url])
            ->one();

        if ($model === null) {
            $model = new Log4xx();
            $model->method = $method;
            $model->url = $url;
            $model->code = $statusCode;
            $model->created = date('Y-m-d H:i:s');
        }

        $referrers = [];
        if (strlen($model->referrer) > 0) {
            $referrers = explode(';', $model->referrer);
        }
        if (isset($referrer) && strlen($referrer) > 0) {
            $referrers[] = $referrer;
        }

        $model->referrer = join(';', array_unique(array_filter($referrers)));
        $model->counter += 1;
        $model->modified = date('Y-m-d H:i:s');

        return $model->save(false);
    }
}
