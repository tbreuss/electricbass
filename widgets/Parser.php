<?php

namespace app\widgets;

use app\models\Lesson;
use app\models\Website;
use ParseError;
use Yii;
use yii\base\Widget;
use yii\helpers\Markdown;

class Parser extends Widget
{
    /**
     * @var \yii\db\ActiveRecord;
     */
    public static $MODEL;
    public $model;
    public $attribute;

    public function run()
    {
        // for shortcodes
        static::$MODEL = $this->model;

        $parsed = str_replace('@web/', Yii::getAlias('@web'). '/', $this->model->{$this->attribute});
        $parsed = Yii::$app->shortcode->parse($parsed);

        // remove self-closing br tags
        $parsed = str_replace('<br/>', '<br>', $parsed);

        // check if we have more of such elements
        if (strpos($parsed, '/>') !== false) {
            // TODO Log markdown parse error
        }

        $parsed = Markdown::process($parsed, 'gfm');
        return $parsed;
    }

    public static function alphatab($options, $content)
    {
        // todo: $options['title']
        return self::renderPartial('alphatab', [
            'alphatab' => $content
        ]);
    }

    public static function amazon()
    {
        return '<div class="shortcode shortcode--amazon"></div>';
    }

    public static function articles()
    {
        return '<div class="shortcode shortcode--articles"></div>';
    }

    public static function vimeo(array $options, $content)
    {
        $options = array_merge([
            'key' => '',
            'width' => '',
            'height' => '',
            'responsive' => 1
        ], $options);

        return self::renderPartial('vimeo', $options);
    }

    public static function youtube(array $options, $content)
    {
        $options = array_merge([
            'title' => '',
            'key' => '',
            'width' => '',
            'height' => '',
            'caption' => '',
            'text' => ''
        ], $options);
        self::checkYouTubeVideo($options['key']);
        /*if (Yii::$app->controller->id == 'migration') {
            self::getYouTubeFoto($options['key']);
        }*/
        return self::renderPartial('youtube', $options);
    }

    public static function image(array $options, $content)
    {
        $options = array_merge([
            'title' => '',
            'alt' => '',
            'src' => '',
            'width' => 0,
            'height' => 0,
            'url' => '',
            'copyrightLabel' => '',
            'copyrightUrl' => '',
            'caption' => ''
        ], $options);

        if (!empty($options['src'])) {
            $url = static::resolveImage($options['src']);
            if (!empty($url)) {
                $size = static::getImageSize($url);
                if ($size) {
                    $options['width'] = $size[0];
                    $options['height'] = $size[1];
                }
            }
            $options['url'] = static::resolveImage($options['src']);
            unset($options['src']);
        }

        return self::renderPartial('image', $options);
    }

    private static function getImageSize(string $url): ?array
    {
        $path = Yii::getAlias('@app/web/' . $url);
        try {
            $size = getimagesize($path);
        } catch(\Exception $e) {
            // TODO Fehler protokollieren
            return null;
        }
        if ($size === false) {
            return null;
        }
        if (!isset($size[0]) || !isset($size[1])) {
            return null;
        }
        return [$size[0], $size[1]];
    }

    protected static function resolveImage($src)
    {
        $table = (static::$MODEL)::getTableSchema()->name;

        if (strpos($src, '/') !== false) {
            return $src;
        }

        if ($table == 'search') {
            $table = static::$MODEL->tableName;
        }

        if ($table == 'blog') {
            return sprintf('media/blog/%d/%s', (static::$MODEL)->id, $src);
        }

        if ($table == 'lesson') {
            return sprintf('media/lektion/%d/%s', (static::$MODEL)->id, $src);
        }

        return $src;
    }

    public static function imgtext(array $options, $content)
    {
        $options = array_merge([
            'title' => '',
            'url' => '',
            'src' => '',
            'position' => '',
            'caption' => '',
            'text' => $content,
            'copyrightLabel' => '',
            'copyrightUrl' => ''
        ], $options);

        if (!empty($options['src'])) {
            $options['url'] = static::resolveImage($options['src']);
            unset($options['src']);
        }

        return self::renderPartial('imgtext', $options);
    }

    public static function jsongallery(array $options, $content)
    {
        $options = array_merge([
            'title' => '',
            'gallery' => json_decode($content, true)
        ], $options);

        return self::renderPartial('jsongallery', $options);
    }

    public static function jsonlinks(array $options, $content)
    {
        $options = array_merge([
            'title' => '',
        ], $options);

        $options += json_decode($content, true);

        return self::renderPartial('jsonlinks', $options);
    }

    public static function lessonnav($options, $content)
    {
        $options = array_merge([
            'type' => '',
        ], $options);

        if (Yii::$app->controller->id == 'lesson') {
            $pathInfo = '/' . Yii::$app->request->getPathInfo();
            $models = Lesson::findAllChildren($pathInfo);
            return self::renderPartial('lessonnav', [
                'models' => $models
            ]);
        }
    }

    public static function htmlphp($options, $content)
    {
        $content = self::addWebAlias($content);
        try {
            return self::renderPartial('htmlphp', [
                'content' => $content
            ]);
        } catch (ParseError $e) {
            // TODO Fehler protokollieren
            return $content;
        }
    }

    public static function rssfeed($options, $content)
    {
        return '<div class="shortcode shortcode--rssfeed"></div>';
    }

    /**
     * @param array $options
     * @return string
     */
    public static function score(array $options): string
    {
        $options = array_merge([
            'label' => '',
            'path' => '',
        ], $options);

        // MIDI
        $relMidiPath = sprintf(
            '%s/%s.midi',
            $options['path' ],
            basename($options['path' ])
        );

        $midiPath = Yii::getAlias('@app/web/' . $relMidiPath);
        $midiUrl = Yii::getAlias('@web/' . $relMidiPath);
        $midiSize = is_file($midiPath) ? filesize($midiPath) : 0;
        $midiSize = ($midiSize > 0) ? Yii::$app->formatter->asShortSize($midiSize, 1) : '';

        // PDF
        $relPdfPath = sprintf(
            '%s/%s.pdf',
            $options['path' ],
            basename($options['path' ])
        );

        $pdfPath = Yii::getAlias('@app/web/' . $relPdfPath);
        $pdfUrl = Yii::getAlias('@web/' . $relPdfPath);
        $pdfSize = is_file($pdfPath) ? filesize($pdfPath) : 0;
        $pdfSize = ($pdfSize > 0) ? Yii::$app->formatter->asShortSize($pdfSize, 1) : '';

        $imageUrl = sprintf(
            '/%s/%s.cropped.png',
            $options['path' ],
            basename($options['path' ])
        );

        return self::renderPartial('score', [
            'imageSrc' => $imageUrl,
            'imageAlt' => $options['label'] ?: '',
            'midiUrl' => $midiUrl,
            'midiSize' => $midiSize,
            'pdfUrl' => $pdfUrl,
            'pdfSize' => $pdfSize,
            'scoreName' => $options['label'],
        ]);
    }

    public static function downloads($options, $content)
    {
        if (is_string($options)) {
            $options = [];
        }

        $options = array_merge([
            'title' => 'Downloads',
            'items' => []
        ], $options);

        $content = trim($content);

        if (strlen(trim($content) > 0)) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $line) {
                $cells = explode('|', $line);
                if (!isset($cells[0]) || (strlen($cells[0]) <= 0)) {
                    continue;
                }
                $path = Yii::getAlias('@app/web/' . $cells[0]);

                if (!is_file($path)) {
                    continue;
                }

                $type = strtoupper(pathinfo($path, PATHINFO_EXTENSION));
                $sizeInt = filesize($path);
                $sizeHuman = ($sizeInt > 0) ? Yii::$app->formatter->asShortSize($sizeInt, 1) : '';

                $options['items'][] = [
                    'url' => Yii::getAlias('@web/' . $cells[0]),
                    'label' => $cells[1] ?? $cells[0],
                    'size' => strtoupper($sizeHuman),
                    'type' => $type
                ];
            }
        }

        return self::renderPartial('downloads', $options);
    }

    public static function soundcloud(array $options): string
    {
        $options = array_merge([
            'tracks' => '',
        ], $options);
        return self::renderPartial('soundcloud', [
            'tracks' => $options['tracks']
        ]);
    }

    public static function spotify(array $options): string
    {
        $options = array_merge([
            'artist' => '',
        ], $options);
        return self::renderPartial('spotify', [
            'artist' => $options['artist']
        ]);
    }

    public static function websites(array $options, $content)
    {
        $options = array_merge([
            'countrycode' => '',
            'tags' => ''
        ], $options);

        $models = Website::find()
            ->where('deleted IS NULL AND countryCode=:countrycode AND FIND_IN_SET(:tags, tags)', $options)
            ->orderBy('title ASC')
            ->all();

        return self::renderPartial('websites', [
            'models' => $models
        ]);
    }

    protected static function addWebAlias($html)
    {
        $html = str_replace('"/media/', '"' . Yii::getAlias('@web') . '/media/', $html);
        $html = str_replace("'/media/", "'" . Yii::getAlias('@web') . '/media/', $html);
        return $html;
    }

    protected static function renderPartial($view, $options)
    {
        return Yii::$app->controller->renderPartial('@app/widgets/views/parser/' . $view, $options);
    }

    protected static function checkYouTubeVideo($id)
    {
        $cacheKey = 'youtube-' . $id;
        $data = Yii::$app->cache->get($cacheKey);
        if ($data === false) {
            $headers = get_headers('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $id);
            if (!strpos($headers[0], '200')) {
                $message = sprintf("%s: missing video \"%s\" on page \"%s\"\n", date('Y-m-d H:i:s'), $id, Yii::$app->request->getUrl());
                file_put_contents(Yii::getAlias('@app/runtime/youtube.txt'), $message, FILE_APPEND);
            }
            Yii::$app->cache->set($cacheKey, $headers, 24*60);
        }
    }

    /*protected static function getYouTubeFoto($key)
    {
        $blog = static::$MODEL;
        $dir = Yii::getAlias(sprintf('@webroot/media/blog/%d', $blog->id));

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $file = Yii::getAlias(sprintf('@webroot/media/blog/%d/%s.jpg', $blog->id, $key));

        if (!is_file($file)) {

            $content = @file_get_contents("http://img.youtube.com/vi/$key/0.jpg");
            if (!empty($content)) {
                file_put_contents($file, $content);
            }

        }

        if (is_file($file)) {

            $blog->fotos = $key . '.jpg';
            $blog->save(false, ['fotos']);

        }

    }*/

}
