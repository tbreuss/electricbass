<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string|null $eid
 * @property int $oid
 * @property string|null $countryCode
 * @property string|null $language
 * @property string $platform
 * @property string $key
 * @property int $width
 * @property int $height
 * @property string $title
 * @property string $url
 * @property string $abstract
 * @property string $text
 * @property string $tags
 * @property int $comments
 * @property int $ratings
 * @property float $ratingAvg
 * @property int $hits
 * @property string|null $deleted
 * @property string $created
 * @property string $modified
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            [['oid', 'width', 'height', 'comments', 'ratings', 'hits'], 'integer'],
            [['platform', 'text'], 'string'],
            [['platform', 'created', 'modified'], 'required'],
            [['ratingAvg'], 'number'],
            [['deleted', 'created', 'modified'], 'safe'],
            [['eid'], 'string', 'max' => 8],
            [['countryCode', 'language'], 'string', 'max' => 2],
            [['key'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 100],
            [['url', 'tags'], 'string', 'max' => 255],
            [['abstract'], 'string', 'max' => 500],
            [['url'], 'unique', 'skipOnEmpty' => false],
            [['platform', 'key'], 'unique', 'targetAttribute' => ['platform', 'key']],
            ['height', 'default', 'value' => 99]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'eid' => 'EID',
            'oid' => 'OID',
            'countryCode' => 'Ländercode',
            'language' => 'Sprachcode',
            'platform' => 'Platform',
            'key' => 'Key',
            'width' => 'Breite',
            'height' => 'Höhe',
            'title' => 'Titel',
            'url' => 'Url',
            'abstract' => 'Kurztext',
            'text' => 'Langtext',
            'tags' => 'Tags',
            'comments' => 'Kommentare',
            'ratings' => 'Bewertungen Anzahl',
            'ratingAvg' => 'Bewertungen Durchschnitt',
            'hits' => 'Seitenaufrufe',
            'deleted' => 'Gelöscht am',
            'created' => 'Erstellt am',
            'modified' => 'Geändert am',
        ];
    }
}
