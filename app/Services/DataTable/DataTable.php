<?php

namespace App\services\DataTable;

use App\Services\DataTable\Query;
use App\Services\DataTable\HandleRequest;
use Illuminate\Database\Eloquent\Builder;

class DataTable {

    use HandleRequest;

    protected $query;
    public $repository;

    public function __construct() {
        $this->repository = new Query();
    }

    public function processRequest($request) {

        $this->parseRequest($request);
        return $this;
    }

    public function applySearch(Builder $query) {

        $model = $query->getModel();
        $this->checkCloumnsOfModel($model);
        if (!empty($this->search_values)) {
            $this->query = $this->repository->where($query, $this->search_values);
        }
        return $this;
    }

    protected function checkModelColumns($model) {

        $modelCloumns = $model::getColumns();
        foreach ($this->search_values as $cloumn => $value) {
            if (!in_array($column, $modelColumns)) {
                unset($this->search_values[$column]);
            }
        }
    }

    public function applyWhereBetween(Builder $query = null , $feild = null , $start = null , $end = null) {
 
        $field  = $field  ?? 'created_at';
        $start  = $start  ?? $this->start_date;
        $end    = $end    ?? $this->end_date;
        $query  = $query  ?? $this->query; 

        if ($start && $end) {
            $this->query = $this->repository->whereBetween($query, $feild, $start, $end);
        }
        return $this;
    }
    
    public function applySortByField(Builder $query = null , $feild , $sort = 'asc') {
        
        $sort  = $sort  ?? $this->sort;
        $query = $query ?? $this->query;

        if ($sort) {
            $this->query = $this->repository->sortByField($query, $feild , $sort);
        }
        return $this;
    }

    public function applySearchRelation(Builder $query) {
        
        $queryModel = $query->getModel();
        $relationsModel = $query->getEagerLoads();

        foreach ($relationsModel as $key => $value) {
            $relatedModel = $queryModel->{$key}()->getRelated();
            $relatedSearchValues = $this->checkModelColumns($relatedModel);
            $query = $this->repository->where($query, $relatedSearchValues);
        }
        return $query;
    }
        
    public function searchInRelatedModel(Builder $query, $relatedModel) {
        
        $relatedSearchValues = $this->getRelatedSearchValues($relatedModel);
        if (!empty($relatedSearchValues)) {
             $query = $this->repository->where($query, $relatedSearchValues);
        }
        return $query;
    }
        
    public function getRelatedSearchValues($relatedModel) {
    
        $relatedSearchValues = [];
        if (!empty($this->searchValues)) {
            foreach ($this->searchValues as $key => $value) {
                if (property_exists($relatedModel, $key)) {
                    $relatedSearchValues[$key] = $value;
                 }
                }
                }
        return $relatedSearchValues;
    }
}