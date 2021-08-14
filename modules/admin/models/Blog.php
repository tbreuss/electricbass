<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property int $categoryId
 * @property string $countryCode
 * @property string $language
 * @property string $title
 * @property string $mainTag
 * @property string $url
 * @property string|null $movedTo
 * @property string $subtitle
 * @property string $abstract
 * @property string|null $text
 * @property string $tags
 * @property string $renderer
 * @property string $fotoCopyright
 * @property int $hideFoto
 * @property int $comments
 * @property int $ratings
 * @property float $ratingAvg
 * @property int $hits
 * @property int $featured
 * @property string|null $publication
 * @property string|null $deleted
 * @property string $created
 * @property string $modified
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['movedTo', 'pageTitle', 'metaDescription', 'changes', 'deleted'], 'default', 'value' => null],
            [['categoryId', 'created', 'modified'], 'required'],
            [['categoryId', 'hideFoto', 'comments', 'ratings', 'hits', 'featured'], 'integer'],
            [['abstract', 'text'], 'string'],
            [['ratingAvg'], 'number'],
            [['publication', 'deleted', 'created', 'modified'], 'safe'],
            [['countryCode', 'language'], 'string', 'max' => 2],
            [['title', 'movedTo', 'subtitle'], 'string', 'max' => 100],
            [['pageTitle'], 'string', 'max' => 60],
            [['metaDescription'], 'string', 'max' => 155],
            [['mainTag'], 'string', 'max' => 20],
            [['url', 'tags', 'fotoCopyright'], 'string', 'max' => 255],
            [['renderer'], 'string', 'max' => 16],
            [['url'], 'unique', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryId' => 'Category ID',
            'countryCode' => 'Ländercode',
            'language' => 'Sprachcode',
            'title' => 'Titel',
            'mainTag' => 'Haupttag',
            'url' => 'Url',
            'movedTo' => 'Verschoben nach (URL)',
            'subtitle' => 'Untertitel',
            'abstract' => 'Abstract',
            'text' => 'Text',
            'tags' => 'Tags',
            'renderer' => 'Renderer',
            'fotoCopyright' => 'Foto Copyright',
            'hideFoto' => 'Foto verstecken',
            'comments' => 'Kommentare',
            'ratings' => 'Bewertungen Anzahl',
            'ratingAvg' => 'Bewertungen Durchschnitt',
            'hits' => 'Seitenaufrufe',
            'featured' => 'Featured',
            'publication' => 'VÖ-Datum',
            'deleted' => 'Gelöscht am',
            'created' => 'Erstellt am',
            'modified' => 'Geändert am',
        ];
    }
}
