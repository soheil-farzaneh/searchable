<?php

namespace App\Services\DataTable;

trait HandleRequest {

    protected $search_values = null;
    protected $start_date = null;
    protected $end_date = null;
    protected $sort;

    public function parseRequest($request) {

        if (isset($request['search']))      $this->mapsearch_values($request['search']);
        if (isset($request['date_range']))  $this->mapDataRangeValues($request['date_range']);
        if (isset($request['sort']))        $this->setSortValue($request['sort']);
    }

    protected function mapsearch_values($search_values) {

        if (gettype($search_values) == 'string') $search_values = json_decode($search_values);
        foreach ($search_values as $cloumn => $value) {
            $this->search_values[$cloumn] = $value;
        }
    }

    protected function mapDataRangeValues($date_range) {

        if (gettype($date_range) == 'string') $date_range = json_decode($date_range);
        foreach ($date_range as $key => $value) {
            ($key == 'start_date') ? $this->start_date = $value : $this->end_date = $value;
        }    
    }

    protected function setSortValue($sortValue) {

        $this->sort = $sortValue;
    }

}