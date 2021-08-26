<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Html;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property int $id
 * @property int $autosort
 * @property string $title
 * @property string $category
 * @property string $content
 * @property string $url
 */
final class Glossar extends ActiveRecord
{
    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'Id',
            'category' => 'Kategorie',
            'title' => 'Titel',
            'url' => 'URL',
            'abstract' => 'Kurztext',
            'content' => 'Langtext',
            'frequency' => 'Häufigkeit',
        ];
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            ['category', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['url', 'required'],
            ['abstract', 'required'],
            ['content', 'required'],
        ];
    }

    /**
     * @return Glossar[]
     */
    public function findAllByCategory(string $category): array
    {
        $condition = '';
        $params = [];
        if (!empty($category)) {
            $condition = ' AND category=:category';
            $params = [
                ':category' => strtolower($category)
            ];
        }
        $criteria = [
            'condition' => 'deleted=0 AND hidden=0' . $condition,
            'order' => 'autosort',
            'params' => $params
        ];
        return $this->findAll($criteria);
    }

    /**
     * @param int|string $id
     */
    public static function findOneOrNull($id): ?Glossar
    {
        $glossar = Glossar::find()->where(['deleted' => 0, 'url' => $id])->one();
        if ($glossar) {
            return $glossar;
        }
        $glossar = Glossar::find()->where(['deleted' => 0, 'id' => $id])->one();
        if ($glossar) {
            return $glossar;
        }
        return null;
    }

    /**
     * @param int|string $id
     * @throws NotFoundHttpException
     */
    public static function findOneOrThrowException($id): Glossar
    {
        $glossar = self::findOneOrNull($id);
        if (is_null($glossar)) {
            throw new NotFoundHttpException();
        }
        return $glossar;
    }

    public function findNextOneOrNull(): ?Glossar
    {
        $condition = 'autosort>:autosort AND deleted=0 AND hidden=0';
        $params = [':autosort' => $this->autosort];
        return self::find()->where($condition, $params)->orderBy('autosort ASC')->one();
    }

    public function findPreviousOneOrNull(): ?Glossar
    {
        $condition = 'autosort<:autosort AND deleted=0 AND hidden=0';
        $params = [':autosort' => $this->autosort];
        return self::find()->where($condition, $params)->orderBy('autosort DESC')->one();
    }

    /**
     * @phpstan-return array<int, array<string, string>>
     * @throws \yii\db\Exception
     */
    public static function queryAllCategories(): array
    {
        $command = Yii::$app->db->createCommand('SELECT DISTINCT category FROM glossar WHERE deleted=0 AND hidden=0 ORDER BY category');
        return $command->queryAll();
    }

    public function getDefaultImage(string $alias = ''): string
    {
        return '';
    }

    public function hasDefaultImage(): bool
    {
        return false;
    }

    public function increaseHits(): void
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_GLOSSAR_IDS', []);
        if (!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_GLOSSAR_IDS', $ids);
        }
    }
}
