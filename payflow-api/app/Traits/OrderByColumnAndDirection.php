<?php

namespace App\Traits;

trait OrderByColumnAndDirection
{
    public function orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
    {
        return $query->get()
            ->when($orderByColumn && $orderByDirection, function ($query) use ($orderByColumn, $orderByDirection) {
                $relationsAndField = explode('.', $orderByColumn);
                $sortMethod = $orderByDirection === 'desc' ? 'sortByDesc' : 'sortBy';

                return $query->$sortMethod(function ($model) use ($relationsAndField) {
                    foreach ($relationsAndField as $relation) {
                        $model = $model->$relation;
                    }

                    return $this->removeAccentsAndToLower($model);
                });
            });
    }

    public function removeAccentsAndToLower($value): bool|string
    {
        return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $value));
    }
}
