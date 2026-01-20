<?php

namespace app\widgets;

use app\components\AlphaTabApi;
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
                'alphaTab' => new AlphaTabApi(
                    notation: $this->content,
                    instrument: AlphaTabApi::INSTRUMENT_FOUR_STRING_BASS,
                    debug: $isDebug,
                ),
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
            'alphaTab' => new AlphaTabApi(
                notation: $model->notation,
                options: $model->options,
                instrument: $model->instrument,
                uid: $model->uid,
                debug: $isDebug,
            ),
        ]);
    }
}
