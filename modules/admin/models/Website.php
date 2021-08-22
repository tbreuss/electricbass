<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "website".
 *
 * @property int $id
 * @property string $countryCode
 * @property string $language
 * @property string $title
 * @property string $url
 * @property string $subtitle
 * @property string $abstract
 * @property string|null $content
 * @property string $website
 * @property string $tags
 * @property int $comments
 * @property int $ratings
 * @property float $ratingAvg
 * @property int $hits
 * @property int $status
 * @property string $latitude
 * @property string $longitude
 * @property string $geocodingAddress
 * @property string|null $deleted
 * @property string $created
 * @property string $modified
 */
class Website extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'website';
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            [['created', 'modified'], 'required'],
            [['abstract', 'content', 'geocodingAddress'], 'string'],
            [['comments', 'ratings', 'hits', 'status'], 'integer'],
            [['ratingAvg'], 'number'],
            [['deleted', 'created', 'modified'], 'safe'],
            [['countryCode', 'language'], 'string', 'max' => 2],
            [['title', 'subtitle', 'website'], 'string', 'max' => 100],
            [['url', 'tags'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'string', 'max' => 12],
            [['website'], 'unique', 'skipOnEmpty' => false],
            [['url'], 'unique', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'countryCode' => 'Country Code',
            'language' => 'Language',
            'title' => 'Title',
            'url' => 'Url Segment',
            'subtitle' => 'Subtitle',
            'abstract' => 'Abstract',
            'content' => 'Content',
            'website' => 'Website',
            'tags' => 'Tags',
            'comments' => 'Comments',
            'ratings' => 'Ratings',
            'ratingAvg' => 'Rating Avg',
            'hits' => 'Hits',
            'status' => 'Status',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'geocodingAddress' => 'Geocoding Address',
            'deleted' => 'Deleted',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
