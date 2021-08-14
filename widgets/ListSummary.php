<?php

namespace app\widgets;

use yii\base\Widget;
use yii\data\Pagination;
use yii\data\Sort;

class ListSummary extends Widget
{
    public Pagination $pagination;
    public Sort $sort;

    public function run(): string
    {
        return $this->render('listsummary', [
            'pagination' => $this->pagination,
            'sort' => $this->sort,
            'summary' => $this->getSummary()
        ]);
    }

    protected function getSummary(): string
    {
        return sprintf(
            '%d-%s von %s<span> Ergebnissen</span>',
            $this->pagination->offset + 1,
            min($this->pagination->offset + $this->pagination->pageSize, $this->pagination->totalCount),
            $this->pagination->totalCount
        );
    }

}
