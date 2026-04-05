<?php

namespace app\features\comment;

use app\features\comment\models\Comment;

final class Widget extends \yii\base\Widget
{
    public string $tableName = '';
    public int $tableId = 0;

    public function run(): string
    {
        /** @var Comment[] $model */
        $models = Comment::find()
            ->where('active = 1 AND deleted = 0 AND tableName = :tableName AND tableId = :tableId', [':tableName' => $this->tableName, ':tableId' => $this->tableId])
            ->orderBy('created DESC')
            ->all();

        return $this->render('widget', [
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'models' => $models,
            'count' => count($models),
        ]);
    }
}
