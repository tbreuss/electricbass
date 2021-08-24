<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**
 * Class SchemaBreadcrumbs
 * @package app\components
 * @see https://github.com/orige/yii2-schema-breadcrumbs
 */
final class SchemaBreadcrumbs extends Breadcrumbs
{
    /** @var string */
    public $itemTemplate = '
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            {link}
        </li>
    ';

    /** @var string */
    public $activeItemTemplate = '
        <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            {link}
        </li>
    ';

    public int $totalLinks = 0;

    /** @var array<string, mixed> */
    public array $defaultOptions = [];

    function __construct()
    {
        parent::__construct();

        $this->defaultOptions = [
            'itemscope' => true,
            'itemtype' => 'http://schema.org/BreadcrumbList',
        ];
    }

    public function run(): string
    {
        if (empty($this->links)) {
            return '';
        }

        $links = [];

        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }

        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $this->totalLinks += 1;
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }

        $this->options = array_merge($this->options, $this->defaultOptions);

        return Html::tag($this->tag, implode('', $links), $this->options);
    }

    /**
     * @inheritdoc
     * @param array<string, string> $link
     */
    protected function renderItem($link, $template): string
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }

        if (isset($link['template'])) {
            $template = $link['template'];
        }

        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);

            $span = Html::tag('span', $label, ['itemprop' => 'name']);
            $link = Html::a($span, $link['url'], array_merge(['itemprop' => 'item'], $options));
        } else {
            $link = Html::tag('span', $label, ['itemprop' => 'name']);
        }

        $meta_content = $this->totalLinks + 1;
        $meta = "<meta itemprop=\"position\" content=\"$meta_content\" />";

        $link .= $meta;

        return strtr($template, ['{link}' => $link]);
    }
}
