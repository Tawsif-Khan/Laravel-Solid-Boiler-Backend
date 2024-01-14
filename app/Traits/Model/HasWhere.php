<?php

namespace App\Traits\Model;

trait HasWhere
{
    private function buildWhereQuery($query, $whereConditions)
    {
        foreach ($whereConditions as $orWhereConditions) {
            // $query = $query->where($key, $value);
            $query->where(function ($query) use ($orWhereConditions) {
                foreach ($orWhereConditions as $condition) {
                    $query->orWhere($condition['column'], $condition['operator'], $condition['value']);
                }
            });
        }

        return $query;
    }
}
