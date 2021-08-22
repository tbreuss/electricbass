<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14.08.16
 * Time: 12:28
 */

namespace app\helpers;

use Yii;

final class Media
{
    /** @var array|string[] */
    protected static array $CONTEXT_MAPPING = [
        'advertising' => 'kleinanzeigen',
        'blog' => 'blog',
        'catalog' => 'katalog',
        'album' => 'album'
    ];

    public static function getAlbumImage(int $id): string
    {
        $images = self::getImages('album', $id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getBlogImage(int $id): string
    {
        $images = self::getBlogImages($id);
        return empty($images[0]) ? '' : $images[0];
    }

    /**
     * @return string[]
     */
    public static function getBlogImages(int $id): array
    {
        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/blog/{$id}/*.*";
        return self::globFiles($pattern, $allowed, $webroot);
    }

    /**
     * @param string[] $allowedFiles
     * @return string[]
     */
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

    public static function getCatalogImage(int $id): string
    {
        $images = self::getImages('catalog', $id);
        return empty($images[0]) ? '' : $images[0];
    }

    public static function getLessonImage(int $id): string
    {
        $images = self::getLessonImages($id);
        return empty($images[0]) ? '' : $images[0];
    }

    /**
     * @return string[]
     */
    public static function getLessonImages(int $id): array
    {
        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/blog/{$id}/*.*";
        return self::globFiles($pattern, $allowed, $webroot);
    }

    public static function getWebsiteImage(string $url): string
    {
        $url = str_replace(['http://', 'https://'], '', $url);
        if (strpos($url, 'www.') === 0) {
            $url = substr($url, 4);
        }
        $initial = substr($url, 0, 1);

        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}media/website/{$initial}/{$url}.*";
        $images = self::globFiles($pattern, $allowed, $webroot);

        return empty($images[0]) ? '' : str_replace($webroot, '', $images[0]);
    }

    /**
     * @param array<string, string> $options
     */
    public static function htmlImg(string $context, int $id, array $options): string
    {
        $relpath = self::getImage($context, $id);
        return Html::img('@web/' . $relpath, $options);
    }

    public static function xxx(int $id, string $context): string
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

    public static function hasImage(string $context, int $id): bool
    {
        $images = self::getImages($context, $id);
        return count($images) > 0;
    }

    public static function getImage(string $context, int $id): string
    {
        $relpath = '';
        $images = self::getImages($context, $id);
        if (isset($images[0])) {
            $relpath = $images[0];
        }
        return $relpath;
    }

    /**
     * @return string[]
     */
    public static function getImages(string $context, int $id, string $type = 'media'): array
    {
        $context = self::$CONTEXT_MAPPING[$context];

        $webroot = Yii::getAlias('@webroot/');
        $allowed = ['jpeg', 'jpg', 'png', 'gif'];
        $pattern = "{$webroot}{$type}/{$context}/{$id}-*.*";

        return self::globFiles($pattern, $allowed, $webroot);
    }

}
