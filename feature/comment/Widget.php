<?php

namespace app\feature\comment;

use app\feature\comment\models\Comment;

final class Widget extends \yii\base\Widget
{
    public string $tableName = '';
    public int $tableId = 0;
    /** @var Comment[] */
    public array $models = [];

    public function init(): void
    {
        $this->models = Comment::find()
            ->where('active = 1 AND deleted = 0 AND tableName = :tableName AND tableId = :tableId', [':tableName' => $this->tableName, ':tableId' => $this->tableId])
            ->orderBy('created DESC')
            ->all();
        parent::init();
    }

    public function run(): string
    {
        return $this->render('widget', [
            'models' => $this->models,
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'count' => count($this->models),
        ]);
    }
}
