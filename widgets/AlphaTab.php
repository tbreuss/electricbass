<?php

namespace app\widgets;

use app\models\AlphaTab as AlphaTabModel;
use yii\base\Widget;

final class AlphaTab extends Widget
{
    public int $id = 0;
    public string $uid = '';
    public string $content = '';

    public function __construct(
        private AlphaTabModel $model,
        array $config = [],
    ) {
        parent::__construct($config);
    }

    /**
     * @return string|void
     */
    public function run()
    {
        if ($this->content !== '') {
            return $this->render('alpha-tab', [
                'options' => $this->options(null, null), //$this->options($model->options, $model->tablature),
                'notation' => $this->notation($this->content),
                'uniqid' => bin2hex(random_bytes(4)),
            ]);
        }

        if ($this->id < 1 && $this->uid === '') {
            return;
        }

        $model = $this->uid !== ''
            ? $this->model->findByUid($this->uid)
            : $this->model->findById($this->id);

        if ($model === null) {
            return;
        }

        return $this->render('alpha-tab', [
            'options' => $this->options($model->options, $model->bars_per_row),
            'notation' => $this->notation($model->notation, $model->title, $model->subtitle),
            'uniqid' => bin2hex(random_bytes(4)),
        ]);
    }

    private function notation(string $notation, ?string $title = null, ?string $subtitle = null): string
    {
        $nl = "\n";
        $options = [];

        $defaults = [
            '\clef' => 'bass',
            '\bracketextendmode' => 'noBrackets',
            '\tuning' => 'G2 D2 A1 E1 { hide }',
            '\instrument' => '33',
        ];

        $options[] = '\hideDynamics' . $nl; // always hide dynamics

        foreach ($defaults as $k => $v) {
            if (!str_contains($notation, $k)) {
                $options[] = $k . ' ' . $v . $nl;
            }
        }

        return join('', $options) . ltrim($notation);
    }

    /**
     * @param string[][]|null $options
     */
    private function options(?array $options, ?int $barsPerRow): string
    {
        $options = array_merge_recursive([
            'tex' => true,
            'padding' => [0, 0, 0, 0],
            'barsPerRow' => $barsPerRow ?? -1,
            'layoutMode' => 'Page',
            'display' => [
                'justifyLastSystem' => true,
            ]
        ], $options ?? []);

        return json_encode($options) ?: '';
    }
}
