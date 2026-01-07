<?php

namespace app\controllers;

use app\models\Search;
use app\models\Searchlog;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\Controller;

final class SearchController extends Controller
{
    const MIN_QUERY_LENGTH = 3;

    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ],
        ];
    }

    public function actionIndex(?string $term = null): string
    {
        $searched = $term !== null;
        $term = isset($term) ? trim($term) : '';

        $term = $this->filterTerm($term);

        $provider = null;

        if (!empty($term) && (mb_strlen($term) >= SearchController::MIN_QUERY_LENGTH)) {
            $condition = '';
            $fields = ['title', 'subtitle', 'abstract', 'content'/*, 'tags'*/];
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
        return $this->render('index', [
            'dataProvider' => $provider,
            'term' => $term,
            'searched' => $searched
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
