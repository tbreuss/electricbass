<?php

namespace app\models;

use app\helpers\Div;
use app\helpers\Html;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * @property int $id
 * @property string $email
 * @property string $currency
 * @property string $countryTranslated
 * @property string $title
 * @property string $name
 * @property string $url
 * @property string $new_price
 * @property string $sell_price
 * @property string $phone
 * @property string $homepage
 * @property string $region
 * @property string $geocodingAddress
 * @property float $latitude
 * @property float $longitude
 * @property string $longtext
 * @property string $country
 * @property int $category_id
 * @property int $hidden
 * @property int $deleted
 * @property string $date
 * @property string $created
 * @property string $modified
 * @property string|Expression $reminded
 */
final class Advertisement extends ActiveRecord
{
    /** @var string[] */
    public static array $categories = [
        1 => 'Effektgeräte',
        2 => 'Bassgitarren',
        3 => 'Lautsprecher',
        4 => 'Band sucht Bassist',
        5 => 'Verstärker',
        6 => 'Zubehör',
        7 => 'Lehrmittel',
        8 => 'Unterricht',
        9 => 'Bassist sucht Band',
        10 => 'Sonstiges',
        11 => 'Bandgründung'
    ];

    /** @var string */
    public $nspm;

    /** @var boolean */
    public $expired;

    /** @var mixed */
    public $upload;

    public function init(): void
    {
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'onAfterInsert']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'onAfterUpdate']);
    }

    public static function tableName(): string
    {
        return 'advertisement';
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Kategorie',
            'title' => 'Titel',
            'longtext' => 'Anzeigentext',
            'new_price' => 'Neupreis',
            'sell_price' => 'Verkaufspreis',
            'currency' => 'Währung',
            'name' => 'Name',
            'phone' => 'Telefon',
            'region' => 'Region',
            'country' => 'Land',
            'email' => 'E-Mail',
            'homepage' => 'Website',
            'nspm' => 'NSPM',
            'geocodingAddress' => 'Standort'
        ];
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            // category_id
            ['category_id', 'required'],
            ['category_id', 'integer'],
            // title
            ['title', 'required'],
            ['title', 'detectEmailsAndLinks'],
            ['title', 'filter', 'filter' => 'strip_tags'],
            ['title', 'string', 'max' => 60, 'encoding' => 'utf-8'],
            ['title', 'badWord'],
            // longtext
            ['longtext', 'required'],
            ['longtext', 'detectEmailsAndLinks'],
            ['longtext', 'filter', 'filter' => 'strip_tags'],
            // new_price
            ['new_price', 'double'],
            // sell_price
            ['sell_price', 'double'],
            // currency
            ['currency', 'validateCurrency'],
            ['currency', 'detectEmailsAndLinks'],
            ['currency', 'filter', 'filter' => 'strip_tags'],
            // name
            ['name', 'required'],
            ['name', 'detectEmailsAndLinks'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            // phone
            ['phone', 'detectEmailsAndLinks'],
            ['phone', 'filter', 'filter' => 'strip_tags'],
            // region
            ['region', 'required'],
            ['region', 'detectEmailsAndLinks'],
            ['region', 'filter', 'filter' => 'strip_tags'],
            // country
            ['country', 'required'],
            ['country', 'detectEmailsAndLinks'],
            ['country', 'filter', 'filter' => 'strip_tags'],
            // email
            ['email', 'required'],
            ['email', 'validateBlacklist'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'strip_tags'],
            // homepage
            ['homepage', 'url'],
            // geocodingAddress
            ['geocodingAddress', 'detectEmailsAndLinks'],
            ['geocodingAddress', 'string', 'max' => 128, 'encoding' => 'utf-8'],
            ['geocodingAddress', 'filter', 'filter' => 'strip_tags'],
            // nspm
            ['nspm', 'safe'],
            // upload
            [['upload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, jpeg', 'maxSize' => 1024 * 1024 * 1],
        ];
    }

    public function onAfterInsert(): void
    {
        $this->updateUrlSegment();
        $this->updateGeoAddress();
    }

    public function onAfterUpdate(): void
    {
        $this->updateGeoAddress();
    }

    protected function updateUrlSegment(): void
    {
        $urlSegment = $this->createUrlSegment();
        if (!empty($urlSegment)) {
            $attributes = [
                'url' => $urlSegment
            ];
            self::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        }
    }

    protected function updateGeoAddress(): void
    {
        if (!empty($this->geocodingAddress)) {
            $coords = Div::getGoogleMapCoords($this->geocodingAddress);
            if (!empty($coords) && (count($coords) == 2) && (array_sum($coords) <> 0)) {
                self::updateAll($coords, 'id=:id', ['id' => $this->id]);
            }
        }
    }

    public function validateCurrency(string $attribute): void
    {
        if (!empty($this->new_price) && !empty($this->sell_price)) {
            if (empty($this->currency)) {
                $this->addError($attribute, 'Währung darf nicht leer sein.');
            }
        }
    }

    public function validateBlacklist(string $attribute): void
    {
        foreach (AdvertisementBadEmail::findRegexPatterns() as $regexPattern) {
            $pattern = '/^' . $regexPattern . '$/i';
            if (preg_match($pattern, $this->email)) {
                $this->addError($attribute, 'E-Mail steht in unserer Blacklist.');
                break;
            }
        }
    }

    public function badWord(string $attribute): void
    {
        if (mb_strlen($this->$attribute) === 0) {
            return;
        }

        $words = preg_split("/\W+/u", $this->$attribute);
        if ($words === false) {
            return;
        }

        $badWords = AdvertisementBadWord::findBadWords();

        foreach ($words as $word) {
            $word = mb_convert_encoding($word, 'UTF-8');
            $word = mb_strtolower($word, 'UTF-8');
            if (array_search($word, $badWords) !== false) {
                $this->addError($attribute, 'Unser Spamfilter hat mindestens ein ungültiges Wort entdeckt.');
            }
        }
    }

    public function detectEmailsAndLinks(string $attribute): void
    {
        $label = $this->getAttributeLabel($attribute);
        if (Div::detectEmails($this->$attribute)) {
            $this->addError($attribute, sprintf('%s darf keine E-Mails enthalten.', $label));
        }
        if (Div::detectLinks($this->$attribute)) {
            $this->addError($attribute, sprintf('%s darf keine Links enthalten.', $label));
        }
    }

    /**
     * @return array<string, string>
     */
    public function getInfos(): array
    {
        #$flag = Html::flagImage($this->country);

        $infos = [];
        if (!empty($this->new_price)) {
            $infos['Neupreis'] = $this->new_price . ' ' . $this->currency;
        }
        if (!empty($this->sell_price)) {
            $infos['Verkaufspreis'] = $this->sell_price . ' ' . $this->currency;
        }
        if (!empty($this->name)) {
            $infos['Name'] = $this->name;
        }
        if (!empty($this->region)) {
            $infos['Region'] = $this->region . ' / ' . $this->countryTranslated;
        }
        if (!empty($this->phone)) {
            $infos['Telefon'] = $this->phone;
        }
        #if(!empty($this->email)) $infos['E-Mail'] = Html::fancyboxIframeLink('Anbieter kontaktieren', array('advertisement/contact', 'id' => $this->id), array('class' => 'fancybox-iframe', 'target'=>'_blank'));
        #if(!empty($this->email)) $infos['E-Mail'] = Html::a('Anbieter kontaktieren', array('advertisement/contact', 'id' => $this->id), array('class' => 'button button--danger'));
        if (!empty($this->homepage)) {
            $infos['Website'] = Html::a(mb_strimwidth($this->homepage, 0, 50, '...', 'UTF-8'), $this->homepage, ['rel' => 'nofollow', 'target' => '_blank']);
        }
        return array_map('stripslashes', $infos);
    }

    public function handleUpload(): bool
    {
        $upload = UploadedFile::getInstance($this, 'upload');
        if (is_null($upload)) {
            return false;
        }

        // mehrere whitespaces durch einen ersetzen
        $title = preg_replace('/\s+/', '-', $this->title);
        if (!is_string($title)) {
            $title = 'upload';
        }

        // nur buchstaben, ziffern, binde-/unterstrich und punkt erlauben
        $title = preg_replace("/[^A-Za-z0-9-_.]+/i", "", $title);
        if (!is_string($title)) {
            $title = 'upload';
        }

        // kleinbuchstaben
        $title = strtolower($title);

        $filename = sprintf(
            'media/kleinanzeigen/%s-%s.%s',
            $this->id,
            $title,
            $upload->getExtension()
        );

        $saved = $upload->saveAs($filename);
        if (!$saved) {
            return false;
        }

        // resize image (respect ratio)
        $imagine = new Imagine();
        $imagine->open($filename)
            ->thumbnail(new Box(1024, 1024))
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($filename, ['jpeg_quality' => 90, 'png_compression_level' => 8]);

        return true;
    }

    public function getPhoto(): string
    {
        $webRoot = Yii::getAlias('@webroot/');
        if ($webRoot === false) {
            return '';
        }

        $pattern = sprintf('%smedia/kleinanzeigen/%d-*.*', $webRoot, $this->id);
        $result = glob($pattern, GLOB_NOSORT);
        if ($result === false) {
            return '';
        }

        if (isset($result[0])) {
            return str_replace($webRoot, '', $result[0]);
        }
        return '';
    }

    public function getPageTitle(): string
    {
        return stripslashes(sprintf('%s - %d | %s | %s', $this->title, $this->id, $this->getCategory(), 'Kleinanzeige'));
    }

    /**
     * @return Advertisement[]
     */
    public static function findAllForReminders(): array
    {
        return self::find()
            ->select(['*, UNIX_TIMESTAMP(date) AS date, (DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND reminded IS NULL AND DATEDIFF(NOW(), date) BETWEEN 60 AND 67')
            ->orderBy('date DESC')
            ->all();
    }

    /**
     * @return Advertisement[]
     */
    public static function findAllForAdvertisementIndexController(): array
    {
        return self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60')
            ->orderBy('date DESC')
            ->all();
    }

    /**
     * @return Advertisement[]
     */
    public static function findAllForFeed(): array
    {
        return self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60')
            ->orderBy('date DESC')
            ->all();
    }

    /**
     * @param string $email
     * @return Advertisement[]
     */
    public static function findAllByEmail(string $email): array
    {
        return self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('email=:email AND hidden=0 AND deleted=0', [':email' => $email])
            ->orderBy('id DESC')
            ->all();
    }

    /**
     * @return Advertisement[]
     */
    public static function findAllByCoordinate(string $position): array
    {
        $coords = explode(',', $position);
        if (!empty($coords) && count($coords) == 2) {
            $criteria = [
                'select' => '*, UNIX_TIMESTAMP(date) AS date, (DATEDIFF(NOW(), date)>60) AS expired',
                'condition' => 'latitude LIKE :latitude AND longitude LIKE :longitude AND hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60',
                'params' => [':latitude' => $coords[0] . '%', ':longitude' => $coords[1] . '%']
            ];
            #return self::model()->findAll($criteria);
        }
        return [];
    }

    /**
     * @param int|string $id
     * @throws NotFoundHttpException
     */
    public static function findById($id, bool $strict): Advertisement
    {
        $condition = is_numeric($id) ? 'id=:id' : 'url=:id';
        $condition .= empty($strict) ? '' : ' AND hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60';

        $advertisement = self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where($condition, [':id' => $id])
            ->one();

        if (is_null($advertisement)) {
            throw new NotFoundHttpException();
        }
        return $advertisement;
    }

    public function createDetailUrl(bool $scheme = false): string
    {
        return Url::to($this->url, $scheme);
    }

    public function getCategory(): string
    {
        return self::$categories[$this->category_id] ?? '';
    }

    public function getShortenedText(int $chars = 70): string
    {
        $text = preg_replace('/\s\s+/', ' ', $this->longtext);
        if ($text === null) {
            return $this->longtext;
        }
        return mb_strimwidth(strip_tags($text), 0, $chars, '...', 'UTF-8');
    }

    public function createActivationUrl(): string
    {
        $url = ['advertisement/activate', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id)];
        return Url::toRoute($url, true);
    }

    public function createRenewUrl(): string
    {
        $url = ['advertisement/renew', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id)];
        return Url::toRoute($url, true);
    }

    public function createDeleteUrl(int $confirmed = 0): string
    {
        $url = ['advertisement/delete', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id)];
        if ($confirmed) {
            $url['confirmed'] = 1;
        }
        return Url::toRoute($url, true);
    }

    public function createUpdateUrl(): string
    {
        $url = ['advertisement/update', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id)];
        return Url::toRoute($url, true);
    }

    public function renew(): bool
    {
        $attributes = [
            'hidden' => 0,
            'deleted' => 0,
            'date' => new Expression('NOW()'),
            'reminded' => null
        ];
        $numbOfRows = self::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        return ($numbOfRows > 0);
    }

    public function activate(): bool
    {
        $attributes = [
            'hidden' => 0,
            'deleted' => 0,
            'date' => new Expression('NOW()'),
            'reminded' => null
        ];
        $numbOfRows = self::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        return ($numbOfRows > 0);
    }

    public function softDelete(): int
    {
        $attributes = [
            'deleted' => 1
        ];
        return self::updateAll($attributes, 'id=:id', ['id' => $this->id]);
    }

    public function getCountryTranslated(): string
    {
        $countries = ['DE' => 'Deutschland','CH' => 'Schweiz','AT' => 'Österreich'];
        return $countries[$this->country] ?? '';
    }

    public function increaseHits(): void
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_ADVERTISEMENT_IDS', []);
        if (!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_ADVERTISEMENT_IDS', $ids);
        }
    }

    /**
     * @phpstan-return array<int, array<string, string>>
     * @throws \yii\db\Exception
     */
    public static function findLatestAsArray(int $limit = 5): array
    {
        $sql = "
            SELECT id,title,date,url,country,region
            FROM advertisement
            WHERE hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60
            ORDER BY date DESC
            LIMIT {$limit}
        ";
        return Yii::$app->db->createCommand($sql)
            ->queryAll();
    }

    public function createUrlSegment(): string
    {
        $segment = $this->title . '-' . $this->id;
        $segment = str_replace('/', '-', $segment);
        return '/kleinanzeigen/' . Div::normalizeUrlSegment($segment);
    }

    public function unlinkPhoto(): bool
    {
        $fotos = $this->getAllFotos();

        $deleted = 0;
        foreach ($fotos as $foto) {
            if (unlink($foto)) {
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->unlinkCachedImages();
        }

        return ($deleted > 0);
    }

    public function unlinkCachedImages(): bool
    {
        $cachePath = sprintf('cache/kleinanzeigen-%d-*', $this->id);
        $files = glob($cachePath);
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    /**
     * @return array<int, string>
     */
    protected function getAllFotos(): array
    {
        $webRoot = Yii::getAlias('@webroot/');
        if ($webRoot === false) {
            return [];
        }

        $pattern = sprintf('%smedia/kleinanzeigen/%d-*.*', $webRoot, $this->id);
        $results = glob($pattern, GLOB_NOSORT);
        if ($results === false) {
            return [];
        }

        foreach ($results as $i => $result) {
            $results[$i] = str_replace($webRoot, '', $result);
        }

        return $results;
    }
}
