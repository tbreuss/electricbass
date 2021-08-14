<?php

namespace app\widgets;

use Mobile_Detect;
use Yii;
use yii\base\Widget;

class SocialBar extends Widget
{
    public $id;
    public $text;

    public function run()
    {
        $detect = new Mobile_Detect();
        return $this->render('socialbar', [
            'url' => $this->getUrl(),
            'text' => $this->text,
            'isMobile' => $detect->isMobile()
        ]);
    }

    private function getUrl()
    {
        return 'https://www.electricbass.ch/' . Yii::$app->request->pathInfo;
    }

}
