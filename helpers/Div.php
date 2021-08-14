<?php

namespace app\helpers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;

class Div
{

    /**
     * @return array
     */
	public static function getGoogleMapCoords($address) {
        $query = urlencode($address);
        $request = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', $query);
		$response = file_get_contents($request);
        $responseArr = json_decode($response, true);
        if($responseArr['status']=='OK') {
            if(isset($responseArr['results'][0]['geometry']['location'])) {
                $location = $responseArr['results'][0]['geometry']['location'];
                return array(
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng']
                );
            }
        }
        return array(
            'latitude' => 0,
            'longitude' => 0
        );
    }

    /**
     * @param string $id
     * @return string
     */
    public static function createAccessCode($id)
    {
        return md5($id . Yii::$app->params['encryptionKey']);
    }

    /**
     * @param string $json
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function getJsonValue($json, $key, $default=null)
    {
        $values = json_decode($json, true);
        return isset($values[$key]) ? $values[$key] : $default;
    }

    /**
     * @param array $row
     * @return string
     * @throws \Exception
     */
    public static function articleImage($row)
    {
        if(!isset($row['aId'])) {
            throw new \Exception('Fehler in Div::articleImage(). Key aId fehlt in Array $row.');
        }
        if(!empty($row['aFotos'])) {
            if(mb_substr($row['aFotos'],0,7,'utf-8') == 'http://') {
                return $row['aFotos'];
            }
            $filepath = 'media/a/'.$row['aId'].'/'.$row['aFotos'];
            if(is_file($filepath)) {
                return $filepath;
            }
        }
        /*if(!isset($row['cId'], $row['aContent'])) {
            throw new Exception('Key categoryId oder content fehlen in Array $row');
        }*/
        if($row['cId']==7) {
            $data = json_decode($row['aContent']);
            if(isset($data->key) && ($data->service=='YT') && !empty($data->key)) {
                $url = sprintf('http://img.youtube.com/vi/%s/0.jpg', $data->key);
                return $url;
            }
        }
        return '';
    }

    /**
     * @return string
     */
    public static function getInitial($str)
    {
        return strtoupper(mb_substr($str, 0, 1, 'utf-8'));
    }

    /**
     * @return string
     */
    public static function createTempMidiFileName()
    {
        $save_dir = 'tmp';

        $d = dir($save_dir);
        while (false !== ($entry = $d->read())) {
            if(!empty($entry) && ($entry != '.') && ($entry != '..')) {
                $filepath = $save_dir.'/'.$entry;
                $filemtime = filemtime($filepath);
                if(empty($filemtime)) {
                    touch($filepath, time());
                }
                $filemtime = filemtime($filepath);
                if((time() - $filemtime) > 3660) {
                    unlink($filepath);
                }
            }
        }
        $d->close();

        srand((double)microtime()*1000000);
        return $save_dir.'/'.rand().'.mid';
    }

    public static function range($low, $hight, $step=1)
    {
        $rArray = array();
        $tmpRange = range($low, $hight, $step);
        foreach($tmpRange AS $val) {
            $rArray[$val] = $val;
        }
        return $rArray;
    }

    /**
     * @deprecated
     * @param string $str
     * @return string
     */
    public static function removeAccent($str)
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä',  'Å', 'Æ',  'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',  'Ø', 'Ù', 'Ú', 'Û', 'Ü',  'Ý', 'ß',  'à', 'á', 'â', 'ã', 'ä',  'å', 'æ',  'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö',  'ø', 'ù', 'ú', 'û', 'ü',  'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('A', 'A', 'A', 'A', 'Ae', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'Oe', 'O', 'U', 'U', 'U', 'Ue', 'Y', 'ss', 'a', 'a', 'a', 'a', 'ae', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'oe', 'o', 'u', 'u', 'u', 'ue', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        return str_replace($a, $b, $str);
    }

    /**
     * @deprecated
     * @param string $attribute
     * @return string
     */
    public static function normalizeUrlSegment($urlSegment)
    {
        // @see: http://ch2.php.net/manual/de/function.preg-replace.php
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), self::removeAccent($urlSegment)));
    }

    public static function extractTags($models)
    {
        $tags = array();
        foreach($models AS $model) {
            $tags = array_merge($tags, explode(',', $model->tags));
        }
        return array_unique($tags);
    }

    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function hashPassword($password,$salt)
    {
        return md5($salt.$password);
    }

    /**
     * @return string
     */
    public static function generateSalt()
    {
        return uniqid('',true);
    }

    function randStr($length=16, $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
    {
        // Length of character list
        $chars_length = (mb_strlen($chars) - 1);

        // Start our string
        $string = $chars[rand(0, $chars_length)];

        // Generate random string
        for ($i = 1; $i < $length; $i = mb_strlen($string))
        {
            // Grab a random character from our list
            $r = $chars[rand(0, $chars_length)];

            // Make sure the same two characters don't appear next to each other
            if ($r != $string[$i - 1]) $string .=  $r;
        }

        // Return the string
        return $string;
    }

    function uniqueFilename($path, $suffix, $length=8)
    {
        do {
            $file = $path."/".self::randStr($length).'.'.$suffix;
            $fp = fopen($file, 'x');
        }
        while(!$fp);
        fclose($fp);
        return $file;
    }

    /**
     * @param string $tags
     * @return array
     */
    public static function string2array($tags)
    {
        return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param array $tags
     * @return string
     */
    public static function array2string($tags)
    {
        return implode(',',$tags);
    }

    /**
     * Wrapper für PhpThumbFactory::resize
     *
     * @param string $filepath
     * @param integer $width
     * @param integer $height
     * @param integer $jpgQuality
     */
    public static function resizeImage($openPath, $savePath, $width, $height, $jpgQuality=100)
    {
        // resize image
        $imagine = new Imagine();
        $imagine->open($openPath)
            ->thumbnail(new Box($width, $height))
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($savePath, array('jpeg_quality' => $jpgQuality, 'png_compression_level' => 8));
    }

    /**
     * @param string $filename
     * @param string $pathname
     * @return string
     */
    public static function filterFilename($filename, $pathname) {
        $tmp = preg_replace('/^\W+|\W+$/', '', $filename); // remove all non-alphanumeric chars at begin & end of string
        $tmp = preg_replace('/\s+/', '_', $tmp); // compress internal whitespace and replace with _
        $tmp = strtolower(preg_replace('/\W-/', '', $tmp)); // remove all non-alphanumeric chars except _ and -
        return Div::getUniqueFilename($tmp, $pathname);
    }

    /**
     * @param string $filename
     * @param string $pathname
     * @return string
     */
    private static function getUniqueFilename($filename, $pathname)
    {
        $info = pathinfo($filename);
        $i=1;
        while(is_file($pathname.$filename)) {
            $filename = $info['filename'].'-'.$i.'.'.$info['extension'];
            $i++;
            if($i>1000) throw new \Exception('Irgendwas ist beim Upload schiefgelaufen');
        }
        return $filename;
    }

    /**
     * @param string $delim
     * @param string $str
     * @return array
     */
    public static function explode($delim, $str)
    {
        $str = trim($str);
        $parts = empty($str) ? array() : explode($delim, $str);
        $parts = array_map('trim', $parts);
        return $parts;
    }

    /**
     * @param integer $maxFrequency
     * @param integer $maxWidth
     * @return int
     */
    public static function calculateWidth($frequency, $maxFrequency, $maxWidth=600)
    {
        $maxWidth -= 100;
        $width = $maxWidth / $maxFrequency * $frequency;
        $width = floor($width);
        /*
        if($width < 100) {
            $width = 100;
        }*/
        $width += 100;
        return intval($width);
    }

}
