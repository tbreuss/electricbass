<?php

namespace app\feature\advertisement;

final class Widget extends \yii\base\Widget
{
    public string $title = 'Kleinanzeigen';
    public int $limit = 4;

    public function run(): string
    {
        $rows = \app\feature\advertisement\models\Advertisement::findLatestAsArray($this->limit);
        return $this->render('widget', [
            'title' => $this->title,
            'rows' => $rows
        ]);
    }
}
