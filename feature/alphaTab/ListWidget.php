<?php

namespace app\feature\alphaTab;

use app\feature\alphaTab\components\AlphaTabApi;
use app\feature\alphaTab\models\AlphaTab;
use Yii;
use yii\base\Widget;

final class ListWidget extends Widget
{
    public int $id = 0;
    public string $uid = '';
    public string $content = '';

    public function __construct(
        private AlphaTab $model,
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
            return $this->render('list-widget', [
                'alphaTab' => new AlphaTabApi(
                    alphaTex: $this->content,
                    optionsGroup: AlphaTabApi::OPTION_GROUP_DEFAULT,
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

        return $this->render('list-widget', [
            'alphaTab' => new AlphaTabApi(
                alphaTex: $model->alpha_tex,
                optionsGroup: $model->options_group,
                options: $model->options,
                instrument: $model->instrument,
                uid: $model->uid,
                debug: $isDebug,
            ),
        ]);
    }
}
