<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

final class Hits extends Widget
{
    /** @var string[] */
    protected static array $ALLOWED = ['advertisement', 'album', 'blog', 'catalog', 'fingering', 'glossar', 'lesson', 'website', 'video'];
    public string $tableName = '';
    public int $tableId = 0;

    public function run(): string
    {
        if ($this->isNotAllowed() || $this->hasSessionEntry()) {
            return '';
        }
        return $this->render('hits', [
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'url' => $this->getUrl(),
        ]);
    }

    protected function getUrl(): string
    {
        return Url::toRoute(['/api/hits']);
    }

    protected function isNotAllowed(): bool
    {
        if (in_array($this->tableName, self::$ALLOWED)) {
            return false;
        }
        return true;
    }

    protected function hasSessionEntry(): bool
    {
        $sessionKey = $this->getSessionKey();
        $ids = Yii::$app->session->get($sessionKey, []);
        if (in_array($this->tableId, $ids)) {
            return true;
        }
        return false;
    }

    protected function addSessionEntry(): void
    {
        $sessionKey = $this->getSessionKey();
        $ids = Yii::$app->session->get($sessionKey, []);
        if (!in_array($this->tableId, $ids)) {
            $ids[] = $this->tableId;
            Yii::$app->session->set($sessionKey, $ids);
        }
    }

    protected function getSessionKey(): string
    {
        return sprintf('HITS_%s_IDS', strtoupper($this->tableName));
    }

    /**
     * @throws \yii\db\Exception
     */
    public function increaseHits(): bool
    {
        if ($this->isNotAllowed()) {
            return false;
        }

        if ($this->hasSessionEntry()) {
            return false;
        }

        $sql = sprintf(
            "UPDATE {{%s}} SET hits = hits + 1 WHERE id = :tableId",
            $this->tableName
        );
        $updated = Yii::$app->db->createCommand($sql, [
            'tableId' => $this->tableId
        ])->execute();

        $this->addSessionEntry();

        return ($updated > 0);
    }
}
