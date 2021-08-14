<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "lesson".
 *
 * @property int $id
 * @property string|null $path
 * @property int $autosort
 * @property string|null $navtitle
 * @property string $title
 * @property string $url
 * @property string $abstract
 * @property string|null $text
 * @property string $tags
 * @property string $renderer
 * @property string|null $fotos
 * @property int $hideFoto
 * @property int $comments
 * @property int $ratings
 * @property float $ratingAvg
 * @property int $hits
 * @property int $featured
 * @property int $deleted
 * @property string $created
 * @property string $modified
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['autosort'], 'default', 'value' => 0],
            [['deleted', 'created', 'modified'], 'required'],
            [['autosort', 'hideFoto', 'comments', 'ratings', 'hits', 'featured', 'deleted'], 'integer'],
            [['abstract', 'text', 'fotos'], 'string'],
            [['ratingAvg'], 'number'],
            [['created', 'modified'], 'safe'],
            [['path', 'url', 'tags'], 'string', 'max' => 255],
            [['navtitle'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 100],
            [['renderer'], 'string', 'max' => 16],
            [['url'], 'unique', 'skipOnEmpty' => false],
            [['path'], 'unique', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Pfad',
            'autosort' => 'Autosort',
            'navtitle' => 'Menütitel',
            'title' => 'Titel',
            'url' => 'URL',
            'abstract' => 'Abstract',
            'text' => 'Text',
            'tags' => 'Tags',
            'renderer' => 'Renderer',
            'fotos' => 'Fotos',
            'hideFoto' => 'Foto verstecken',
            'comments' => 'Kommentare',
            'ratings' => 'Bewertungen Anzahl',
            'ratingAvg' => 'Bewertungen Durchschnitt',
            'hits' => 'Aufrufe',
            'featured' => 'Featured',
            'deleted' => 'Gelöscht',
            'created' => 'Erstellt am',
            'modified' => 'Geändert am',
        ];
    }
}
