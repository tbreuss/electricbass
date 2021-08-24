<?php

namespace app\widgets;

use yii\base\Widget;
use yii\data\Pagination;
use yii\data\Sort;

final class ListSummary extends Widget
{
    public ?Pagination $pagination = null;
    public ?Sort $sort = null;

    public function run(): string
    {
        return $this->render('listsummary', [
            'sort' => $this->sort,
            'summary' => $this->getSummary()
        ]);
    }

    protected function getSummary(): string
    {
        if ($this->pagination === null) {
            return '';
        }
        return sprintf(
            '%d-%s von %s<span> Ergebnissen</span>',
            $this->pagination->offset + 1,
            min($this->pagination->offset + $this->pagination->pageSize, $this->pagination->totalCount),
            $this->pagination->totalCount
        );
    }
}
