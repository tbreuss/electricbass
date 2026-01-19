<?php

namespace app\widgets;

use app\models\AlphaTab as AlphaTabModel;
use Yii;
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
        $isDebug = Yii::$app->request->getQueryParam('debug') !== null;

        if ($this->content !== '') {
            return $this->render('alpha-tab', [
                'options' => $this->options(null, null, $isDebug),
                'notation' => $this->notation($this->content),
                'uniqid' => uniqid(),
                'isDebug' => $isDebug,
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
            'options' => $this->options($model->options, $model->bars_per_row, $isDebug),
            'notation' => $this->notation($model->notation, $model->title, $model->subtitle),
            'uniqid' => uniqid(),
            'isDebug' => $isDebug,
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
    private function options(?array $options, ?int $barsPerRow, bool $isDebug): string
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

        return json_encode($options, $isDebug ? JSON_PRETTY_PRINT : 0) ?: '';
    }
}
