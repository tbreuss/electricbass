<?php

namespace app\helpers;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use yii\helpers\FileHelper;
use yii\web\View;

class Html extends \yii\helpers\Html
{
    public static function hasMetaDescription(View $view)
    {
        foreach ($view->metaTags as $metaTag) {
            if (strpos($metaTag, 'name="description"') !== false) {
                return true;
            }
        }
        return false;
    }

    public static function tags($tags, $title = '')
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
     * @param string $relUrl
     * @param integer $width
     * @param integer $height
     * @param array $htmlOptions
     * @param bool $absUrl
     * @return string
     * @throws \yii\base\Exception
     */
    public static function cachedCropedImage($relUrl, $width, $height, $htmlOptions = array(), $absUrl = false)
    {
        return self::cachedImage('adaptive', $relUrl, $width, $height, $htmlOptions, $absUrl);
    }

    /**
     * @param string $relUrl
     * @param integer $width
     * @param integer $height
     * @param array $htmlOptions
     * @param boolean $absUrl
     * @return string
     */
    public static function resizeImage($relUrl, $width, $height, $htmlOptions = array(), $absUrl = false)
    {
        return self::cachedImage('normal', $relUrl, $width, $height, $htmlOptions, $absUrl);
    }

    /**
     * @param string $type normal|adaptive
     * @param string $absUrl
     * @param integer $width
     * @param integer $height
     * @param array $htmlOptions
     * @param boolean $returnAbsoluteUrl
     * @return string
     * @throws \yii\base\Exception
     */
    public static function cachedImage($type, $absUrl, $width, $height, $htmlOptions = array(), $returnAbsoluteUrl = false)
    {
        // Macht aus der absoluten eine relative Url
        // Diese Url kann als Pfad benutzt werden
        $relUrl = $absUrl;
        $webRoot = \Yii::getAlias('@web');
        if (!empty($webRoot)) {
            if (strpos($absUrl, $webRoot) === 0) {
                $relUrl = substr($absUrl, strlen($webRoot));
            }
        }
        $relUrl = ltrim($relUrl, '/');

        // Bild mit originaler Breite und Höhe
        if (empty($width) && empty($height)) {
            $size = getimagesize($relUrl);
            $htmlOptions['width'] = $size[0];
            $htmlOptions['height'] = $size[1];
            if ($returnAbsoluteUrl) {
            }
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
            $htmlOptions['width'] = $size[0];
            $htmlOptions['height'] = $size[1];
            if ($returnAbsoluteUrl) {
            }
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

        if ($returnAbsoluteUrl) {
        }

        return Html::img('@web/' . $cachedUrl, $htmlOptions);
    }

    /**
     * @param string $src
     * @param string $alt
     * @param array $htmlOptions
     * @param boolean $returnAbsoluteUrl
     * @return string
     */
    /*public static function image($src, $alt = '', $htmlOptions = array(), $returnAbsoluteUrl = false)
    {
        if (mb_substr($src, 0, 7, 'utf-8') != 'http://') {
            $src = baseUrl() . '/' . $src;
            if ($returnAbsoluteUrl) {
                $src = 'http://' . $_SERVER['HTTP_HOST'] . $src;
            }
        }
        $htmlOptions['alt'] = $alt;
        return parent::img($src, $htmlOptions);
    }*/

}
