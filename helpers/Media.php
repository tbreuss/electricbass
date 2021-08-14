<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14.08.16
 * Time: 12:28
 */

namespace app\helpers;

use Yii;

class Media
{
    protected static $CONTEXT_MAPPING = [
        'advertising' => 'kleinanzeigen',
        'blog' => 'blog',
        'catalog' => 'katalog',
        'album' => 'album'
    ];

    public static function getAlbumImage($id)
    {
        $images = static::getImages('album', $id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getBlogImage($id)
    {
        $images = static::getBlogImages($id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getBlogImages($id): array
    {
        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/blog/{$id}/*.*";
        return static::globFiles($pattern, $allowed, $webroot);
    }

    private static function globFiles(string $pattern, array $allowedFiles, string $webroot): array
    {
        $results = glob($pattern);
        $files = [];

        if ($results === false) {
            return $files;
        }

        foreach ($results as $result) {
            $extension = pathinfo($result, PATHINFO_EXTENSION);
            if (in_array($extension, $allowedFiles)) {
                $files[] = str_replace($webroot, '', $result);
            }
        }

        return array_unique($files);
    }

    public static function getCatalogImage($id)
    {
        $images = static::getImages('catalog', $id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getLessonImage($id)
    {
        $images = static::getLessonImages($id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getLessonImages($id)
    {
        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/blog/{$id}/*.*";
        return static::globFiles($pattern, $allowed, $webroot);
    }

    public static function getWebsiteImage($url)
    {
        $url = str_replace(['http://', 'https://'], '', $url);
        if (strpos($url, 'www.') === 0) {
            $url = substr($url, 4);
        }
        $initial = substr($url, 0, 1);

        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/website/{$initial}/{$url}.*";
        $images = static::globFiles($pattern, $allowed, $webroot);

        return empty($images[0]) ? '' : str_replace($webroot, '', $images[0]);
    }

    public static function htmlImg($context, $id, $options)
    {
        $relpath = static::getImage($context, $id);
        return Html::img('@web/' . $relpath, $options);
    }

    public static function xxx($id, $context)
    {

        $relPaths = [
            sprintf('media/blog/%s/%s', $id, $id),
            sprintf('media/a/%s/%s', $id, $id)
        ];

        foreach ($relPaths as $relPath) {
            $absPath = Yii::getAlias('@webroot/' . $relPath);
            if (is_file($absPath)) {
                $url = Yii::getAlias('@web/' . $relPath);
                if ($url !== false) {
                    return $url;
                }
            }
        }

        return '';
    }

    public static function hasImage($context, $id)
    {
        $images = static::getImages($context, $id);
        return count($images) > 0;
    }

    /**
     * @param string $context
     * @param int $id
     * @return string Die URL ohne vorangehenden Slash "/"
     */
    public static function getImage(string $context, int $id): string
    {
        $relpath = '';
        $images = static::getImages($context, $id);
        if (isset($images[0])) {
            $relpath = $images[0];
        }
        return $relpath;
    }

    public static function getImages($context, $id, $type = 'media')
    {
        $context = static::$CONTEXT_MAPPING[$context];

        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}{$type}/{$context}/{$id}-*.*";

        return static::globFiles($pattern, $allowed, $webroot);
    }

}
