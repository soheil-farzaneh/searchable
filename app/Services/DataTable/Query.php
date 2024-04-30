<?php

namespace App\Services\DataTable;

class Query {

    public function where($query, $values) {

        foreach ($values as $items => $item) {
            $query = $query->where($items, 'LIKE', '%' . $item . '%');
        }
        return $query;
    }

    public function whereBetween($query, $field , $start , $end) {

        return $query->whereBetween($field, [$start, $end]);
    }

    public function sortByField($query, $field, $direction) {

        return $query->orderBy($field, $direction);
    }

   

}