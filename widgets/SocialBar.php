<?php

namespace app\widgets;

use Mobile_Detect;
use Yii;
use yii\base\Widget;

final class SocialBar extends Widget
{
    public ?int $id = null;
    public string $text = '';

    public function run(): string
    {
        $detect = new Mobile_Detect();
        return $this->render('socialbar', [
            'url' => $this->getUrl(),
            'text' => $this->text,
            'isMobile' => $detect->isMobile()
        ]);
    }

    private function getUrl(): string
    {
        return 'https://www.electricbass.ch/' . Yii::$app->request->pathInfo;
    }
}
