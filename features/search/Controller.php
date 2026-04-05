<?php

namespace app\features\search;

use app\features\search\models\Search;
use app\features\search\models\Searchlog;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    const MIN_QUERY_LENGTH = 3;

    public function actionIndex(?string $term = null): string
    {
        if (array_diff_key(Yii::$app->request->getQueryParams(), ['term' => ''])) {
            throw new GoneHttpException();
        }
        $defaults = [
            'page' => '1',
            'sort' => 'title',
        ];

        $page = (int)Yii::$app->request->getBodyParam('page', $defaults['page']);
        $sort = Yii::$app->request->getBodyParam('sort', $defaults['sort']);

        $searched = $term !== null;
        $term = isset($term) ? trim($term) : '';

        $term = $this->filterTerm($term);

        $provider = null;

        if (!empty($term) && (mb_strlen($term) >= Controller::MIN_QUERY_LENGTH)) {
            $condition = '';
            $fields = ['title', 'subtitle', 'abstract', 'content', 'keywords'];
            $queryParts = explode(' ', $term);
            $queryParts = array_filter($queryParts, [$this, 'filterQueryParts']);

            if (count($queryParts) > 0) {
                $conditions = [];
                foreach ($fields as $field) {
                    $subConditions = [];
                    foreach ($queryParts as $queryPart) {
                        //$subConditions[] = "$field LIKE '%$queryPart%'";
                        $queryPart = addslashes($queryPart);
                        $subConditions[] = "$field REGEXP '[[:<:]]{$queryPart}[[:>:]]'";
                    }
                    $conditions[] = '(' . implode(' AND ', $subConditions) . ')';
                }
                $condition = implode(' OR ', $conditions);
            }

            $sortObj = new Sort([
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
                'defaultOrder' => str_starts_with($sort, '-') ? [substr($sort, 1) => SORT_DESC] : [$sort => SORT_ASC],
            ]);

            $query = Search::find()
                ->select('tableName, tableId, context, id, title, abstract, url, category')
                ->where($condition)
                ->orderBy($sortObj->orders);

            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'page' => $page - 1, // zero-based
                    'defaultPageSize' => 20,
                ],
                'sort' => $sortObj,
            ]);
        }

        if (!empty($term) && empty($_GET['page']) && isset($provider) && ($provider->getTotalCount() > 0)) {
            Searchlog::addTerm($term, $provider->getTotalCount());
        }

        $this->layout = 'onecol_empty';
        return $this->render('@app/features/search/views/index', [
            'dataProvider' => $provider,
            'term' => $term,
            'searched' => $searched,
            'urlFragments' => [
                'applied' => ['page' => $page, 'sort' => $sort],
                'defaults' => $defaults,
            ],
        ]);
    }

    private function filterQueryParts(string $queryPart): bool
    {
        return !empty($queryPart);
    }

    private function filterTerm(string $term): string
    {
        // @see http://de.wikipedia.org/wiki/Regulärer_Ausdruck
        $filteredTerm1 = preg_replace('/[[:cntrl:]]/', '', $term);
        if (!is_string($filteredTerm1)) {
            return $term;
        }
        $filteredTerm2 = preg_replace('/([ ]{2,})/', ' ', $filteredTerm1);
        if (!is_string($filteredTerm2)) {
            return $filteredTerm1;
        }
        return $filteredTerm2;
    }
}
