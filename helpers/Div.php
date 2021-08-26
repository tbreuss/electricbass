<?php

namespace app\helpers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;

final class Div
{

    /**
     * @phpstan-return array{"latitude": float, "longitude": float}
     */
    public static function getGoogleMapCoords(string $address): array
    {
        $default = array(
            'latitude' => 0,
            'longitude' => 0
        );

        $query = urlencode($address);
        $request = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', $query);
        $response = file_get_contents($request);

        if (is_bool($response)) {
            return $default;
        }

        $responseArr = json_decode($response, true);
        if (is_array($responseArr) && ($responseArr['status'] == 'OK')) {
            if (isset($responseArr['results'][0]['geometry']['location'])) {
                $location = $responseArr['results'][0]['geometry']['location'];
                return array(
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng']
                );
            }
        }

        return $default;
    }

    public static function createAccessCode(int $id): string
    {
        $string = $id . Yii::$app->params['encryptionKey'];
        return md5($string);
    }

    public static function getJsonValue(string $json, string $key, ?string $default = null): string
    {
        $values = json_decode($json, true);
        return $values[$key] ?? $default;
    }

    /**
     * @param array<string, mixed> $row
     * @throws \Exception
     */
    public static function articleImage(array $row): string
    {
        if (!isset($row['aId'])) {
            throw new \Exception('Fehler in Div::articleImage(). Key aId fehlt in Array $row.');
        }
        if (!empty($row['aFotos'])) {
            if (mb_substr($row['aFotos'], 0, 7, 'utf-8') == 'http://') {
                return $row['aFotos'];
            }
            $filepath = 'media/a/' . $row['aId'] . '/' . $row['aFotos'];
            if (is_file($filepath)) {
                return $filepath;
            }
        }
        /*if(!isset($row['cId'], $row['aContent'])) {
            throw new Exception('Key categoryId oder content fehlen in Array $row');
        }*/
        if ($row['cId'] == 7) {
            /** @var array<string, string> $data */
            $data = json_decode($row['aContent'], true);
            if (isset($data['key']) && ($data['service'] == 'YT') && !empty($data['key'])) {
                $url = sprintf('http://img.youtube.com/vi/%s/0.jpg', $data['key']);
                return $url;
            }
        }
        return '';
    }

    public static function getInitial(string $str): string
    {
        return strtoupper(mb_substr($str, 0, 1, 'utf-8'));
    }

    /**
     * @return array<int, int>
     */
    public static function range(int $low, int $hight, int $step = 1): array
    {
        $rArray = array();
        $tmpRange = range($low, $hight, $step);
        foreach ($tmpRange as $val) {
            $rArray[$val] = $val;
        }
        return $rArray;
    }

    /**
     * @deprecated
     */
    public static function removeAccent(string $str): string
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä',  'Å', 'Æ',  'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',  'Ø', 'Ù', 'Ú', 'Û', 'Ü',  'Ý', 'ß',  'à', 'á', 'â', 'ã', 'ä',  'å', 'æ',  'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö',  'ø', 'ù', 'ú', 'û', 'ü',  'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('A', 'A', 'A', 'A', 'Ae', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'Oe', 'O', 'U', 'U', 'U', 'Ue', 'Y', 'ss', 'a', 'a', 'a', 'a', 'ae', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'oe', 'o', 'u', 'u', 'u', 'ue', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        return str_replace($a, $b, $str);
    }

    /**
     * @deprecated
     */
    public static function normalizeUrlSegment(string $urlSegment): string
    {
        // @see: http://ch2.php.net/manual/de/function.preg-replace.php
        $normalizedSegment = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), self::removeAccent($urlSegment));
        if (!is_string($normalizedSegment)) {
            return $urlSegment;
        }
        return strtolower($normalizedSegment);
    }

    /**
     * @param object[] $models
     * @return string[]
     */
    public static function extractTags(array $models): array
    {
        $tags = [];
        foreach ($models as $model) {
            /* @phpstan-ignore-next-line */
            $tags = array_merge($tags, explode(',', $model->tags));
        }
        return array_unique($tags);
    }

    public static function hashPassword(string $password, string $salt): string
    {
        return md5($salt . $password);
    }

    public static function generateSalt(): string
    {
        return uniqid('', true);
    }

    public function randStr(int $length = 16, string $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'): string
    {
        // Length of character list
        $chars_length = (mb_strlen($chars) - 1);

        // Start our string
        $string = $chars[rand(0, $chars_length)];

        // Generate random string
        for ($i = 1; $i < $length; $i = mb_strlen($string)) {
            // Grab a random character from our list
            $r = $chars[rand(0, $chars_length)];

            // Make sure the same two characters don't appear next to each other
            if ($r != $string[$i - 1]) {
                $string .=  $r;
            }
        }

        // Return the string
        return $string;
    }

    /**
     * @param string[] $tags
     */
    public static function array2string(array $tags): string
    {
        return implode(',', $tags);
    }

    public static function resizeImage(string $openPath, string $savePath, int $width, int $height, int $jpgQuality = 100): void
    {
        // resize image
        $imagine = new Imagine();
        $imagine->open($openPath)
            ->thumbnail(new Box($width, $height))
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($savePath, array('jpeg_quality' => $jpgQuality, 'png_compression_level' => 8));
    }

    /**
     * @throws \Exception
     */
    public static function filterFilename(string $filename, string $pathname): string
    {
        $tmp = preg_replace('/^\W+|\W+$/', '', $filename); // remove all non-alphanumeric chars at begin & end of string
        if (!is_string($tmp)) {
            return $filename;
        }
        $tmp = preg_replace('/\s+/', '_', $tmp); // compress internal whitespace and replace with _
        if (!is_string($tmp)) {
            return $filename;
        }
        $tmp = preg_replace('/\W-/', '', $tmp);
        if (!is_string($tmp)) {
            return $filename;
        }
        $tmp = strtolower($tmp); // remove all non-alphanumeric chars except _ and -
        return Div::getUniqueFilename($tmp, $pathname);
    }

    /**
     * @throws \Exception
     */
    private static function getUniqueFilename(string $filename, string $pathname): string
    {
        $info = pathinfo($filename);
        $i = 1;
        while (is_file($pathname . $filename)) {
            $filename = $info['filename'] . '-' . $i;
            if (!empty($info['extension'])) {
                $filename .= '.' . $info['extension'];
            }
            $i++;
            if ($i > 1000) {
                throw new \Exception('Irgendwas ist beim Upload schiefgelaufen');
            }
        }
        return $filename;
    }

    public static function calculateWidth(int $frequency, int $maxFrequency, int $maxWidth = 600): int
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
