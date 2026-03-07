<?php

namespace app\features\fingering\models;

use app\components\traits\SimilarModelsByTags;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $title_nominative
 * @property string $title_accusative
 * @property string $title_genitive
 * @property string $root
 * @property string $position
 * @property string $notes
 * @property string $fingering
 * @property string $strings
 * @property string $abstract
 * @property string $category
 * @property string $url
 */
final class Fingering extends ActiveRecord
{
    use SimilarModelsByTags;

    /**
     * @return Fingering[]|array
     */
    public static function findAllByCategory(string $category): array
    {
        $orderBy = match ($category) {
            'intervall' => 'sorting ASC',
            default => 'title ASC',
        };

        return self::find()
            ->select('title, url, notes, fingering')
            ->where('deleted=0')
            ->andWhere(['category' => $category])
            ->orderBy($orderBy)
            ->all();
    }

    /**
     * @param int|string $id
     */
    public static function findOneOrNull($id): ?Fingering
    {
        $model = self::find()->where(['deleted' => 0, 'url' => $id])->one();
        if ($model) {
            return $model;
        }
        $model = self::find()->where(['deleted' => 0, 'id' => $id])->one();
        if ($model) {
            return $model;
        }
        return null;
    }

    public function increaseHits(): void
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_FINGERING_IDS', []);
        if (!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_FINGERING_IDS', $ids);
        }
    }

    public function getNotesInStandardFormat(): array
    {
        $search = ['k', 'g', 'v', 'r', 'u'];
        $replace = ['m', 'M', 'd', 'P', 'A'];
        $notes = explode('-', $this->notes);
        foreach ($notes as $index => $note) {
            $notes[$index] = str_replace($search, $replace, $note);
            $notes[$index] = $notes[$index] === '1' ? 'P1' : $notes[$index];
        }
        return $notes;
    }

    public static function convertNotesToOldFormat(string $note): string
    {
        $search = ['P1', 'P8', 'm', 'M', 'd', 'P', 'A'];
        $replace = ['1', '8',  'b', '',  'd', '',  '#'];
        return str_replace($search, $replace, $note);
    }

    public function categoryAsGenitive(): string
    {
        $genitives = [
            'intervall' => 'des Intervalls',
            'akkord' => 'dieses Arpeggios',
            'tonleiter' => 'der Tonleiter',
        ];
        return $genitives[$this->category];
    }

    public function categoryAsAccusative(): string
    {
        $accusatives = [
            'intervall' => 'dieses Intervall',
            'akkord' => 'dieses Arpeggio',
            'tonleiter' => 'diese Tonleiter',
        ];
        return $accusatives[$this->category];
    }
}
