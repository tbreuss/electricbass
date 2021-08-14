<?php

namespace app\filters;

use app\helpers\Url;
use yii\base\ActionFilter;

class Canonical extends ActionFilter
{
    public $filters = [];
    public $params = [];

    public function beforeAction($action)
    {
        if (!$this->isFilteredRequest()) {
            return parent::beforeAction($action);
        }

        $url = [$this->owner->getRoute()];

        foreach ($this->params as $key) {
            if (isset($_GET[$key])) {
                $url[$key] = $_GET[$key];
            }
        }

        if (!empty($_GET['page'])) {
            $url['page'] = intval($_GET['page']);
        }

        \Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => Url::to($url, true)
        ]);

        return parent::beforeAction($action);
    }

    /**
     * Schaut anhand des Requests, ob dieser im Sinne von Google und SEO eine Anfrage zu einer
     * gefilterten Liste darstellt. Eine solche Anfrage erkennt Google als duplizierten Inhalt.
     *
     * Beispiele: /blog?filter=bass, /blog?tag=jaco
     *
     * @return bool
     */
    private function isFilteredRequest(): bool
    {
        foreach ($this->filters as $filter) {
            if (isset($_GET[$filter]) && (strlen($_GET[$filter]) > 0)) {
                return true;
            }
        }
        return false;
    }

}
