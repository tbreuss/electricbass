<?php

namespace app\controllers;

use app\models\Search;
use app\models\Searchlog;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\Controller;

class SearchController extends Controller
{
    const MIN_QUERY_LENGTH = 3;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['sort'],
                'params' => ['term']
            ]
        ];
    }

    public function actionIndex(?string $term = null): string
    {
        $searched = $term !== NULL;
        $term = trim($term);

        // @see http://de.wikipedia.org/wiki/Regulärer_Ausdruck
        $term = preg_replace('/[[:cntrl:]]/', '', $term);
        $term = preg_replace('/([ ]{2,})/', ' ', $term);

        $provider = null;

        if (!empty($term) && (mb_strlen($term) >= SearchController::MIN_QUERY_LENGTH)) {

            $condition = '';
            $fields = array('title', 'subtitle', 'abstract', 'content'/*, 'tags'*/);
            $queryParts = explode(' ', $term);
            $queryParts = array_filter($queryParts, array($this, 'filterQueryParts'));

            if (count($queryParts) > 0) {

                $conditions = array();
                foreach ($fields AS $field) {
                    $subConditions = array();
                    foreach ($queryParts AS $queryPart) {
                        //$subConditions[] = "$field LIKE '%$queryPart%'";
                        $queryPart = addslashes($queryPart);
                        $subConditions[] = "$field REGEXP '[[:<:]]{$queryPart}[[:>:]]'";
                    }
                    $conditions[] = '(' . implode(' AND ', $subConditions) . ')';
                }
                $condition = implode(' OR ', $conditions);
            }

            $sort = new Sort([
                'attributes' => [
                    'rating' => [
                        'asc' => ['ratingAvg' => SORT_ASC, 'ratings' => SORT_DESC],
                        'desc' => ['ratingAvg' => SORT_DESC, 'ratings' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Bewertung',
                    ],
                    'title' => [
                        'asc' => ['title' => SORT_ASC],
                        'desc' => ['title' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label' => 'Titel',
                    ],
                    'category' => [
                        'asc' => ['context' => SORT_ASC, 'title' => SORT_ASC],
                        'desc' => ['context' => SORT_DESC, 'title' => SORT_ASC],
                        'default' => ['context' => SORT_DESC, 'title' => SORT_ASC],
                        'label' => 'Kategorie',
                    ],
                    'comments' => [
                        'asc' => ['comments' => SORT_ASC],
                        'desc' => ['comments' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Kommentare',
                    ],
                ],
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ]);

            $query = Search::find()
                ->select('tableName, tableId, context, id, title, abstract, url, category')
                ->where($condition)
                ->orderBy($sort->orders);

            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'defaultPageSize' => 20,
                ],
                'sort' => $sort,
            ]);

        }

        if (!empty($term) && empty($_GET['page']) && isset($provider) && ($provider->getTotalCount() > 0)) {
            Searchlog::addTerm($term, $provider->getTotalCount());
        }

        $this->layout = 'onecol_empty';
        return $this->render('index', array(
            'dataProvider' => $provider,
            'term' => $term,
            'searched' => $searched
        ));
    }

    /**
     * Callback für array_filter funktion
     * @param array $queryPart
     * @return bool
     */
    private function filterQueryParts($queryPart)
    {
        return !empty($queryPart);
    }

}
