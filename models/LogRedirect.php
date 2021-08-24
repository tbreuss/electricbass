<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property string $request
 * @property ?string $referrer
 * @property string $redirect
 * @property string $created
 * @property string $signal
 * @property int $code
 * @property ?string $userAgent
 * @property int $counter
 * @property string $modified
 */
final class LogRedirect extends ActiveRecord
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
        $model = self::find()
            ->where(['request' => $request])
            ->one();

        if ($model === null) {
            $model = new self();
            $model->request = $request;
            $model->created = $now;
        }

        $referrers = [];
        if (isset($model->referrer) && strlen($model->referrer) > 0) {
            $referrers = explode(';', $model->referrer);
        }
        if (isset($referrer) && strlen($referrer) > 0) {
            $referrers[] = $referrer;
        }
        $referrer = join(';', array_unique(array_filter($referrers)));

        $model->signal = $signal;
        $model->code = $code;
        $model->referrer = strlen($referrer) > 0 ? $referrer : null;
        $model->redirect = $redirect;
        $model->userAgent = (isset($userAgent) && strlen($userAgent)) > 0 ? $userAgent : null;
        ;
        $model->counter += 1;
        $model->modified = $now;

        return $model->save(false);
    }
}
