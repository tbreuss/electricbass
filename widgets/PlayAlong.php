<?php

namespace app\widgets;

use app\models\PlayAlong as PlayAlongModel;
use yii\base\Widget;

final class PlayAlong extends Widget
{
    public int $id = 0;
    public string $uid = '';

    public function __construct(
        private PlayAlongModel $model,
        array $config = [],
    ) {
        parent::__construct($config);
    }

    public function run()
    {
        if ($this->id < 1 && $this->uid === '') {
            return;
        }

        $model = $this->uid !== ''
            ? $this->model->findByUid($this->uid)
            : $this->model->findById($this->id);

        if ($model === null) {
            return;
        }

        return $this->render('play-along', [
            'id' => $model->id,
            'title' => $model->title,
            'options' => $this->options($model->options, $model->tablature),
            'notation' => $this->notation($model->notation),
            'uniqid' => bin2hex(random_bytes(4)),
        ]);
    }

    private function notation(string $notation): string
    {
        return htmlspecialchars(str_replace(["\n", "\r"], '\n', $notation), ENT_COMPAT);
    }

    /**
     * @param string[][]|null $options
     */
    private function options(?array $options, ?string $tablature): string
    {
        if (is_null($options)) {
            $options = [];
        }

        $tablatureObject = match ($tablature) {
            'FOUR_STRING_BASS' => [['tuning' => ['E,,', 'A,,', 'D,', 'G,'], 'instrument' => 'violin']],
            'FIVE_STRING_BASS' => [['tuning' => ['B,,,', 'E,,', 'A,,', 'D,', 'G,'], 'instrument' => 'guitar']],
            'SIX_STRING_BASS' => [['tuning' => ['B,,,', 'E,,', 'A,,', 'D,', 'G,', 'C,'], 'instrument' => 'guitar']],
            default => null,
        };

        if (isset($tablatureObject)) {
            $options['tablature'] = $tablatureObject;
        }

        return json_encode($options) ?: '';
    }
}
