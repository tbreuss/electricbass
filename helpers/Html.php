<?php

namespace app\helpers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use yii\helpers\FileHelper;
use yii\web\View;

final class Html extends \yii\helpers\Html
{
    public static function hasMetaDescription(View $view): bool
    {
        foreach ($view->metaTags as $metaTag) {
            if (strpos($metaTag, 'name="description"') !== false) {
                return true;
            }
        }
        return false;
    }

    public static function tags(string $tags, string $title = ''): string
    {
        if (empty($tags)) {
            return '';
        }
        $html = self::beginTag('div', ['class' => 'widget widget-tags']);
        if (!empty($title)) {
            $html .= self::tag('h2', $title);
        }
        $html .= self::beginTag('p');
        foreach (explode(',', $tags) as $tag) {
            $html .= self::tag('span', $tag, ['class' => 'label label-default']);
            $html .= ' ';
        }
        $html .= self::endTag('p');
        $html .= self::endTag('div');
        return $html;
    }

    /**
     * @param array<string, string> $htmlOptions
     * @throws \yii\base\Exception
     */
    public static function cachedCropedImage(string $relUrl, int $width, int $height, array $htmlOptions = [], bool $absUrl = false): string
    {
        return self::cachedImage('adaptive', $relUrl, $width, $height, $htmlOptions, $absUrl);
    }

    /**
     * @param array<string, string> $htmlOptions
     * @throws \yii\base\Exception
     */
    public static function resizeImage(string $relUrl, int $width, int $height, array $htmlOptions = [], bool $absUrl = false): string
    {
        return self::cachedImage('normal', $relUrl, $width, $height, $htmlOptions, $absUrl);
    }

    /**
     * @param array<string, string> $htmlOptions
     * @throws \yii\base\Exception
     */
    public static function cachedImage(string $type, string $absUrl, int $width, int $height, array $htmlOptions = [], bool $returnAbsoluteUrl = false): string
    {
        // Macht aus der absoluten eine relative Url
        // Diese Url kann als Pfad benutzt werden
        $relUrl = $absUrl;

        $webRoot = \Yii::getAlias('@web');
        if ($webRoot === false) {
            return ''; // TODO return not found image
        }

        if (strlen($webRoot) > 0) {
            if (str_starts_with($absUrl, $webRoot)) {
                $relUrl = substr($absUrl, strlen($webRoot));
            }
        }

        $relUrl = ltrim($relUrl, '/');

        // Bild mit originaler Breite und Höhe
        if (empty($width) && empty($height)) {
            $size = getimagesize($relUrl);
            if ($size === false) {
                return ''; // TODO return not found image
            }
            $htmlOptions['width'] = $size[0];
            $htmlOptions['height'] = $size[1];
            return Html::img($absUrl, $htmlOptions);
        }

        // Zwischengespeichertes Bild
        $cachedUrl = str_replace('media/', '', $relUrl);
        $parts = pathinfo(str_replace(array(':', '/'), '-', $cachedUrl));
        $cachedUrl = sprintf(
            'cache/%s-%dx%d%s%s',
            $parts['filename'],
            $width,
            $height,
            substr($type, 0, 1),
            empty($parts['extension']) ? '' : '.' . $parts['extension']
        );

        // Erstes "-" mit "/" ersetzen
        $pos = strpos($cachedUrl, '-');
        if ($pos !== false) {
            $cachedUrl[$pos] = '/';
        }

        if (is_file($cachedUrl)) {
            $size = getimagesize($cachedUrl);
            if ($size === false) {
                return ''; // TODO return not found image
            }
            $htmlOptions['width'] = $size[0];
            $htmlOptions['height'] = $size[1];
            return Html::img('@web/' . $cachedUrl, $htmlOptions);
        }

        $cachedDir = dirname($cachedUrl);
        if (!is_dir($cachedDir)) {
            FileHelper::createDirectory($cachedDir, 0775, true);
        }

        $imagine = new Imagine();

        if ($type == 'adaptive') {
            $imagine->open($relUrl)
                ->resize(new Box($width, $height ))
                ->interlace(ImageInterface::INTERLACE_PLANE)
                ->save($cachedUrl, array('jpeg_quality' => 80, 'png_compression_level' => 8));

        } else {
            $imagine->open($relUrl)
                ->thumbnail(new Box($width, $height ))
                ->interlace(ImageInterface::INTERLACE_PLANE)
                ->save($cachedUrl, array('jpeg_quality' => 80, 'png_compression_level' => 8));

        }

        return Html::img('@web/' . $cachedUrl, $htmlOptions);
    }

}
