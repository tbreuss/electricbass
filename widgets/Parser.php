<?php

namespace app\widgets;

use app\models\Lesson;
use app\models\Website;
use Yii;
use yii\base\Widget;
use yii\helpers\Markdown;

final class Parser extends Widget
{
    /** @var \app\models\Blog|\app\models\Lesson|\app\models\Search */
    public static $MODEL;
    /** @var \app\models\Blog|\app\models\Lesson|\app\models\Search */
    public $model;
    /** @var string */
    public $attribute;

    public function run(): string
    {
        // for shortcodes
        self::$MODEL = $this->model;

        $parsed = str_replace('@web/', Yii::getAlias('@web') . '/', $this->model->{$this->attribute});
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

    /**
     * @phpstan-param array<string, mixed> $options
     */
    public static function alphatab($options, string $content): string
    {
        // todo: $options['title']
        return self::renderPartial('alphatab', [
            'alphatab' => $content
        ]);
    }

    public static function amazon(): string
    {
        return '<div class="shortcode shortcode--amazon"></div>';
    }

    public static function articles(): string
    {
        return '<div class="shortcode shortcode--articles"></div>';
    }

    /**
     * @phpstan-param array<string, int|string> $options
     */
    public static function vimeo(array $options, string $content): string
    {
        $options = array_merge([
            'key' => '',
            'width' => '',
            'height' => '',
            'responsive' => 1
        ], $options);

        return self::renderPartial('vimeo', $options);
    }

    /**
     * @phpstan-param array<string, string> $options
     */
    public static function youtube(array $options, string $content): string
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

    /**
     * @phpstan-param array<string, mixed> $options
     */
    public static function image(array $options, string $content): string
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
            $url = self::resolveImage($options['src']);
            if (!empty($url)) {
                $size = self::getImageSize($url);
                if ($size) {
                    $options['width'] = $size[0];
                    $options['height'] = $size[1];
                }
            }
            $options['url'] = self::resolveImage($options['src']);
            unset($options['src']);
        }

        return self::renderPartial('image', $options);
    }

    /**
     * @phpstan-return array{int, int}|null
     * @todo Log error
     */
    private static function getImageSize(string $url): ?array
    {
        try {
            $path = Yii::getAlias('@app/web/' . $url);
            if (is_bool($path)) {
                return null;
            }
            $size = getimagesize($path);
            if ($size === false) {
                return null;
            }
            if (!isset($size[0]) || !isset($size[1])) {
                return null;
            }
            return [$size[0], $size[1]];
        } catch (\Throwable) {
            return null;
        }
    }

    protected static function resolveImage(string $src): string
    {
        $table = (self::$MODEL)::getTableSchema()->name;

        if (strpos($src, '/') !== false) {
            return $src;
        }

        if ($table == 'search') {
            if (isset(self::$MODEL->tableName)) {
                $table = self::$MODEL->tableName;
            }
        }

        if ($table == 'blog') {
            return sprintf('media/blog/%d/%s', (self::$MODEL)->id, $src);
        }

        if ($table == 'lesson') {
            return sprintf('media/lektion/%d/%s', (self::$MODEL)->id, $src);
        }

        return $src;
    }

    /**
     * @phpstan-param array<string, string> $options
     */
    public static function imgtext(array $options, string $content): string
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
            $options['url'] = self::resolveImage($options['src']);
            unset($options['src']);
        }

        return self::renderPartial('imgtext', $options);
    }

    /**
     * @phpstan-param array<string, mixed> $options
     */
    public static function jsongallery(array $options, string $content): string
    {
        $options = array_merge([
            'title' => '',
            'gallery' => json_decode($content, true)
        ], $options);

        return self::renderPartial('jsongallery', $options);
    }

    /**
     * @phpstan-param array{"title": string} $options
     */
    public static function jsonlinks(array $options, string $content): string
    {
        $options = array_merge([
            'title' => '',
        ], $options);

        $options += json_decode($content, true);

        return self::renderPartial('jsonlinks', $options);
    }

    /**
     * @phpstan-param array{"type": string} $options
     */
    public static function lessonnav($options): string
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
        return '';
    }

    /**
     * @param string|array $options
     * @phpstan-param string|array<string, mixed> $options
     */
    public static function htmlphp($options, string $content): string
    {
        $content = self::addWebAlias($content);
        try {
            return self::renderPartial('htmlphp', [
                'content' => $content
            ]);
        } catch (\Throwable) {
            // TODO Fehler protokollieren
            return $content;
        }
    }

    public static function rssfeed(): string
    {
        return '<div class="shortcode shortcode--rssfeed"></div>';
    }

    /**
     * @phpstan-param array{"label": string, "path": string} $options
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
        if (is_string($midiPath) && is_string($midiUrl) && is_file($midiPath)) {
            $fileSize = filesize($midiPath);
            $midiSizeAsInt = is_int($fileSize) ? $fileSize : 0;
            $midiSize = ($midiSizeAsInt > 0) ? Yii::$app->formatter->asShortSize($midiSizeAsInt, 1) : '';
        } else {
            $midiUrl = '';
            $midiSize = '';
        }

        // PDF
        $relPdfPath = sprintf(
            '%s/%s.pdf',
            $options['path' ],
            basename($options['path' ])
        );

        $pdfPath = Yii::getAlias('@app/web/' . $relPdfPath);
        $pdfUrl = Yii::getAlias('@web/' . $relPdfPath);
        if (is_string($pdfPath) && is_string($pdfUrl) && is_file($pdfPath)) {
            $fileSize = filesize($pdfPath);
            $pdfSizeAsInt = is_int($fileSize) ? $fileSize : 0;
            $pdfSize = ($pdfSizeAsInt > 0) ? Yii::$app->formatter->asShortSize($pdfSizeAsInt, 1) : '';
        } else {
            $pdfUrl = '';
            $pdfSize = '';
        }

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

    /**
     * @param string|array $options
     * @phpstan-param string|array<string, mixed> $options
     */
    public static function downloads($options, string $content): string
    {
        if (is_string($options)) {
            $options = [];
        }

        $options = array_merge([
            'title' => 'Downloads',
            'items' => []
        ], $options);

        $content = trim($content);

        if (strlen(trim($content)) > 0) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $line) {
                $cells = explode('|', $line);
                if (!isset($cells[0]) || (strlen($cells[0]) <= 0)) {
                    continue;
                }
                $path = Yii::getAlias('@app/web/' . $cells[0]);
                if ($path === false) {
                    continue;
                }

                if (!is_file($path)) {
                    continue;
                }

                $type = strtoupper(pathinfo($path, PATHINFO_EXTENSION));

                $sizeInt = filesize($path);
                if ($sizeInt === false) {
                    continue;
                }

                $sizeHuman = ($sizeInt > 0) ? Yii::$app->formatter->asShortSize($sizeInt, 1) : '';

                $url = Yii::getAlias('@web/' . $cells[0]);
                if ($url === false) {
                    continue;
                }

                $options['items'][] = [
                    'url' => $url,
                    'label' => $cells[1] ?? $cells[0],
                    'size' => strtoupper($sizeHuman),
                    'type' => $type
                ];
            }
        }

        return self::renderPartial('downloads', $options);
    }

    /**
     * @phpstan-param array{"tracks": string} $options
     */
    public static function soundcloud(array $options): string
    {
        $options = array_merge([
            'tracks' => '',
        ], $options);
        return self::renderPartial('soundcloud', [
            'tracks' => $options['tracks']
        ]);
    }

    /**
     * @phpstan-param array{"artist": string} $options
     */
    public static function spotify(array $options): string
    {
        $options = array_merge([
            'artist' => '',
        ], $options);
        return self::renderPartial('spotify', [
            'artist' => $options['artist']
        ]);
    }

    /**
     * @phpstan-param array{"countrycode": string, "tags": string} $options
     */
    public static function websites(array $options, string $content): string
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

    protected static function addWebAlias(string $html): string
    {
        $html = str_replace('"/media/', '"' . Yii::getAlias('@web') . '/media/', $html);
        return str_replace("'/media/", "'" . Yii::getAlias('@web') . '/media/', $html);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected static function renderPartial(string $view, array $options): string
    {
        return Yii::$app->controller->renderPartial('@app/widgets/views/parser/' . $view, $options);
    }

    protected static function checkYouTubeVideo(string $id): void
    {
        $cacheKey = 'youtube-' . $id;
        $data = Yii::$app->cache->get($cacheKey);
        if ($data === false) {
            $headers = get_headers('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $id);
            if ($headers === false) {
                return;
            }
            if (!strpos($headers[0], '200')) {
                $message = sprintf("%s: missing video \"%s\" on page \"%s\"\n", date('Y-m-d H:i:s'), $id, Yii::$app->request->getUrl());
                $alias = Yii::getAlias('@app/runtime/youtube.txt');
                if ($alias === false) {
                    return;
                }
                file_put_contents($alias, $message, FILE_APPEND);
            }
            Yii::$app->cache->set($cacheKey, $headers, 24 * 60);
        }
    }
}
