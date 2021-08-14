<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class Hits extends Widget
{
    protected static $ALLOWED = ['advertisement', 'album', 'blog', 'catalog', 'fingering', 'glossar', 'lesson', 'website', 'video'];
    public $tableName;
    public $tableId;

    /**
     * @return string
     */
    public function run()
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

    /**
     * @return string
     */
    protected function getUrl()
    {
        return Url::toRoute(['/api/hits']);
    }

    /**
     * @return bool
     */
    protected function isNotAllowed()
    {
        if (in_array($this->tableName, self::$ALLOWED)) {
            return false;
        }
        return true;
    }

    protected function hasSessionEntry()
    {
        $sessionKey = $this->getSessionKey();
        $ids = Yii::$app->session->get($sessionKey, []);
        if(in_array($this->tableId, $ids)) {
            return true;
        }
        return false;
    }

    protected function addSessionEntry()
    {
        $sessionKey = $this->getSessionKey();
        $ids = Yii::$app->session->get($sessionKey, []);
        if(!in_array($this->tableId, $ids)) {
            $ids[] = $this->tableId;
            Yii::$app->session->set($sessionKey, $ids);
        }
    }

    protected function getSessionKey()
    {
        $key = sprintf('HITS_%s_IDS', strtoupper($this->tableName));
        return $key;
    }

    /**
     * @return bool
     */
    public function increaseHits()
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
