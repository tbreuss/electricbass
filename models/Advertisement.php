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
 */
class Advertisement extends ActiveRecord
{

    public static array $categories = array(
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
    );

    public static $blacklist = array(
        'gabriel.buehler@gmx.ch'
    );

    /**
     * @var string
     */
    public $nspm;

    /**
     * @var boolean
     */
    public $expired;

    public $upload;

    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'onAfterInsert']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'onAfterUpdate']);
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'advertisement';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
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
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            // category_id
            array('category_id', 'required'),
            // title
            array('title', 'required'),
            array('title', 'filter', 'filter' => 'strip_tags'),
            array('title', 'string', 'max' => 60, 'encoding' => 'utf-8'),
            // longtext
            array('longtext', 'required'),
            array('longtext', 'filter', 'filter' => 'strip_tags'),
            // new_price
            array('new_price', 'double'),
            // sell_price
            array('sell_price', 'double'),
            // currency
            array('currency', 'validateCurrency'),
            array('currency', 'filter', 'filter' => 'strip_tags'),
            // name
            array('name', 'required'),
            array('name', 'filter', 'filter' => 'strip_tags'),
            // phone
            array('phone', 'filter', 'filter' => 'strip_tags'),
            // region
            array('region', 'required'),
            array('region', 'filter', 'filter' => 'strip_tags'),
            // country
            array('country', 'required'),
            array('country', 'filter', 'filter' => 'strip_tags'),
            // email
            array('email', 'required'),
            array('email', 'validateBlacklist'),
            array('email', 'email'),
            array('email', 'filter', 'filter' => 'strip_tags'),
            // homepage
            array('homepage', 'url'),
            // geocodingAddress
            array('geocodingAddress', 'string', 'max' => 128, 'encoding' => 'utf-8'),
            // nspm
            array('nspm', 'safe'),
            // upload
            [['upload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, jpeg', 'maxSize' => 1024 * 1024 * 1],
        );
    }

    public function onAfterInsert()
    {
        $this->updateUrlSegment();
        $this->updateGeoAddress();
    }

    public function onAfterUpdate()
    {
        $this->updateGeoAddress();
    }

    /**
     * Aktualisiert das URL-Segment
     */
    protected function updateUrlSegment()
    {
        $urlSegment = $this->createUrlSegment();
        if(!empty($urlSegment)) {
            $attributes = [
                'url' => $urlSegment
            ];
            static::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        }
    }

    /**
     * Aktualisiert Längen- und Breitengrad
     */
    protected function updateGeoAddress()
    {
        if(!empty($this->geocodingAddress)) {
            $coords = Div::getGoogleMapCoords($this->geocodingAddress);
            if(!empty($coords) && (count($coords)==2) && (array_sum($coords)<>0)) {
                static::updateAll($coords, 'id=:id', ['id' => $this->id]);
            }
        }
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateCurrency($attribute, $params)
    {
        if (!empty($this->new_price) && !empty($this->sell_price)) {
            if (empty($this->currency)) {
                $this->addError($attribute, 'Währung darf nicht leer sein.');
            }
        }
    }

    public function validateBlacklist($attribute, $params)
    {
        if (in_array($this->email, self::$blacklist)) {
            $this->addError($attribute, 'E-Mail steht in unserer Blacklist.');
        }
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        #$flag = Html::flagImage($this->country);

        $infos = array();
        if (!empty($this->new_price)) $infos['Neupreis'] = $this->new_price . ' ' . $this->currency;
        if (!empty($this->sell_price)) $infos['Verkaufspreis'] = $this->sell_price . ' ' . $this->currency;
        if (!empty($this->name)) $infos['Name'] = $this->name;
        if (!empty($this->region)) $infos['Region'] = $this->region . ' / ' . $this->countryTranslated;
        if (!empty($this->phone)) $infos['Telefon'] = $this->phone;
        #if(!empty($this->email)) $infos['E-Mail'] = Html::fancyboxIframeLink('Anbieter kontaktieren', array('advertisement/contact', 'id' => $this->id), array('class' => 'fancybox-iframe', 'target'=>'_blank'));
        #if(!empty($this->email)) $infos['E-Mail'] = Html::a('Anbieter kontaktieren', array('advertisement/contact', 'id' => $this->id), array('class' => 'button button--danger'));
        if (!empty($this->homepage)) $infos['Website'] = Html::a(mb_strimwidth($this->homepage, 0, 50, '...', 'UTF-8'), $this->homepage, array('rel' => 'nofollow', 'target' => '_blank'));
        return array_map('stripslashes', $infos);
    }

    /**
     * @return bool
     */
    public function handleUpload(): bool
    {
        $upload = UploadedFile::getInstance($this, 'upload');
        if (is_null($upload)) {
            return false;
        }

        // mehrere whitespaces durch einen ersetzen
        $title = preg_replace('/\s+/', '-', $this->title);

        // nur buchstaben, ziffern, binde-/unterstrich und punkt erlauben
        $title = preg_replace("/[^A-Za-z0-9-_.]+/i", "", $title);

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
            ->save($filename, array('jpeg_quality' => 90, 'png_compression_level' => 8));

        return true;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        $webRoot = Yii::getAlias('@webroot/');
        $pattern = sprintf('%smedia/kleinanzeigen/%d-*.*', $webRoot, $this->id);
        $result = glob($pattern, GLOB_NOSORT);
        if (isset($result[0])) {
            $filename = str_replace($webRoot, '', $result[0]);
            return $filename;
        }
        return '';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return stripslashes(sprintf('%s - %d | %s | %s', $this->title, $this->id, $this->getCategory(), 'Kleinanzeige'));
    }

    /**
     * @return array
     */
    public static function findAllForReminders()
    {
        $models = self::find()
            ->select(['*, UNIX_TIMESTAMP(date) AS date, (DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND reminded IS NULL AND DATEDIFF(NOW(), date) BETWEEN 60 AND 67')
            ->orderBy('date DESC')
            ->all();
        return $models;
    }

    /**
     *
     * @return array
     */
    public static function findAllForAdvertisementIndexController()
    {
        $models = self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60')
            ->orderBy('date DESC')
            ->all();
        return $models;
    }

    /**
     *
     * @return array
     */
    public static function findAllForFeed()
    {
        $models = self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60')
            ->orderBy('date DESC')
            ->all();
        return $models;
    }

    /**
     * @param string $email
     * @return array|Advertisement[]
     */
    public static function findAllByEmail($email)
    {
        $models = self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where('email=:email AND hidden=0 AND deleted=0', array(':email' => $email))
            ->orderBy('id DESC')
            ->all();
        return $models;
    }

    public static function findAllByCoordinate($position)
    {
        $coords = explode(',', $position);
        if (!empty($coords) && count($coords) == 2) {
            $criteria = array(
                'select' => '*, UNIX_TIMESTAMP(date) AS date, (DATEDIFF(NOW(), date)>60) AS expired',
                'condition' => 'latitude LIKE :latitude AND longitude LIKE :longitude AND hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60',
                'params' => array(':latitude' => $coords[0] . '%', ':longitude' => $coords[1] . '%')
            );
            #return self::model()->findAll($criteria);
        }
        return array();
    }

    /**
     * @param int|string $id
     * @param bool $strict
     * @return self
     * @throws NotFoundHttpException
     */
    public static function findById($id, bool $strict)
    {
        $condition = is_numeric($id) ? 'id=:id' : 'url=:id';
        $condition .= empty($strict) ? '' : ' AND hidden=0 AND deleted=0 AND DATEDIFF(NOW(), date) < 60';

        $advertisement = self::find()
            ->select(['*', 'UNIX_TIMESTAMP(date) AS date', '(DATEDIFF(NOW(), date)>60) AS expired'])
            ->where($condition, array(':id' => $id))
            ->one();

        if (is_null($advertisement)) {
            throw new NotFoundHttpException(sprintf('Die Kleinanzeige (ID=%s) konnte nicht gefunden werden.', $id));
        }
        return $advertisement;
    }

    public function createDetailUrl($scheme = false)
    {
        return Url::to($this->url, $scheme);
    }

    public function getCategory()
    {
        return isset(self::$categories[$this->category_id]) ? self::$categories[$this->category_id] : '';
    }

    public function getShortenedText($chars = 70)
    {
        $text = preg_replace('/\s\s+/', ' ', $this->longtext);
        return mb_strimwidth(strip_tags($text), 0, $chars, '...', 'UTF-8');
    }

    public function createActivationUrl()
    {
        $url = array('advertisement/activate', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id));
        return Url::toRoute($url, true);
    }

    public function createRenewUrl()
    {
        $url = array('advertisement/renew', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id));
        return Url::toRoute($url, true);
    }

    public function createDeleteUrl($confirmed = 0)
    {
        $url = array('advertisement/delete', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id));
        if ($confirmed) $url['confirmed'] = 1;
        return Url::toRoute($url, true);
    }

    public function createUpdateUrl()
    {
        $url = array('advertisement/update', 'id' => $this->id, 'accessCode' => Div::createAccessCode($this->id));
        return Url::toRoute($url, true);
    }

    public function renew()
    {
        $attributes = [
            'hidden' => 0,
            'deleted' => 0,
            'date' => new Expression('NOW()'),
            'reminded' => null
        ];
        $numbOfRows = static::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        return ($numbOfRows > 0);
    }

    public function activate()
    {
        $attributes = [
            'hidden' => 0,
            'deleted' => 0,
            'date' => new Expression('NOW()'),
            'reminded' => null
        ];
        $numbOfRows = static::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        return ($numbOfRows > 0);
    }

    public function delete()
    {
        $attributes = [
            'deleted' => 1
        ];
        $numbOfRows = static::updateAll($attributes, 'id=:id', ['id' => $this->id]);
        return ($numbOfRows > 0);
    }

    public function getCountryTranslated()
    {
        $countries = ['DE'=>'Deutschland','CH'=>'Schweiz','AT'=>'Österreich'];
        return isset($countries[$this->country]) ? $countries[$this->country] : '';
    }

    public function increaseHits()
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_ADVERTISEMENT_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_ADVERTISEMENT_IDS', $ids);
        }
    }

    public static function findLatestAsArray($limit=5)
    {
        $limit = intval($limit);
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

    public function createUrlSegment()
    {
        $segment = $this->title.'-'.$this->id;
        $segment = str_replace('/', '-', $segment);
        return '/kleinanzeigen/' . Div::normalizeUrlSegment($segment);
    }

    /**
     * @return bool
     */
    public function unlinkPhoto()
    {
        $fotos = $this->getAllFotos();

        $deleted = 0;
        foreach ($fotos as $foto) {
            if(unlink($foto)) {
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->unlinkCachedImages();
        }

        return ($deleted > 0);
    }

    public function unlinkCachedImages()
    {
        $cachePath = sprintf('cache/kleinanzeigen-%d-*', (int)$this->id);
        foreach(glob($cachePath) AS $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * @return array
     */
    protected function getAllFotos()
    {
        $webRoot = Yii::getAlias('@webroot/');
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
