<?php

namespace app\widgets;

use yii\base\Widget;
use yii\data\Pagination;
use yii\data\Sort;

final class ListSummary extends Widget
{
    public Pagination $pagination;
    public Sort $sort;

    public function run(): string
    {
        [$sortLabel, $sortDirection] = $this->getSelectedSort();
        return $this->render('listsummary', [
            'sort' => $this->sort,
            'sortLabel' => $sortLabel,
            'sortDirection' => $sortDirection,
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

    /**
     * @return string[]
     */
    protected function getSelectedSort(): array
    {
        $currentSort = $this->sort->getAttributeOrders();
        if (count($currentSort) === 0) {
            return ['', ''];
        }
        $currentSortDir = reset($currentSort);
        $currentSortKey = key($currentSort);
        foreach ($this->sort->attributes as $sortKey => $sortAttribute) {
            if ($currentSortKey === $sortKey) {
                $label = $sortAttribute['label'];
                if ($currentSortDir === reset($sortAttribute['asc'])) {
                    $direction = 'asc';
                } else {
                    $direction = 'desc';
                }
                return [$label, $direction];
            }
        }
        return ['', ''];
    }
}
