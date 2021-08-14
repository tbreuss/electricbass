<?php

namespace app\models;

use yii\db\ActiveRecord;

class LogRedirect extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{log_redirect}}';
    }

    public static function logRedirect(string $signal, int $code, string $request, string $redirect, ?string $referrer, ?string $userAgent, string $now): bool
    {
        $model = static::find()
            ->where(['request' => $request])
            ->one();

        if ($model === null) {
            $model = new static();
            $model->request = $request;
            $model->created = $now;
        }

        $referrers = [];
        if (strlen($model->referrer) > 0) {
            $referrers = explode(';', $model->referrer);
        }
        if (strlen($referrer) > 0) {
            $referrers[] = $referrer;
        }
        $referrer = join(';', array_unique(array_filter($referrers)));

        $model->signal = $signal;
        $model->code = $code;
        $model->referrer = strlen($referrer) > 0 ? $referrer : null;
        $model->redirect = $redirect;
        $model->userAgent = strlen($userAgent) > 0 ? $userAgent : null;;
        $model->counter += 1;
        $model->modified = $now;

        return $model->save(false);
    }
}
