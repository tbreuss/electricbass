<?php

namespace app\modules\admin;

use Yii;
use yii\helpers\Url;
use yii\web\ErrorHandler;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->layout = 'admin';
        parent::init();
        Yii::$app->setComponents([
            'errorHandler' => [
                'class' => 'yii\web\ErrorHandler',
                'errorAction' => '/admin/default/error'
            ],
            'user' => [
                'class'=>'yii\web\User',
                'identityClass' => 'app\modules\admin\models\User',
                'enableAutoLogin' => true,
                'loginUrl' => Url::to(['/admin/default/login'])
            ],
        ]);

        /** @var ErrorHandler $handler */
        $handler = $this->get('errorHandler');
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }
}
