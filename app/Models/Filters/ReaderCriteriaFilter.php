<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ReaderCriteriaFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if (!is_array($value)) {
            return $query;
        }

        if (isset($value['group_id']) && $value['group_id'] !== 'all') {
            $query->whereHas('recipientAssigns', function ($query) use ($value) {
                $query->where('group_id', $value['group_id']);
            });
        }

        if (isset($value['situation']) && $value['situation'] !== 'all') {
            $query->where('situation', $value['situation']);
        }

        if (isset($value[0]) && is_array($value[0])) {
            $query->where(function ($query) use ($value) {
                foreach ($value as $orKey => $andConditions) {
                    if (!is_array($andConditions)) continue;
                    
                    $query->orWhere(function ($query) use ($andConditions) {
                        foreach ($andConditions as $andKey => $condition) {
                            $this->processCondition($query, $condition);
                        }
                    });
                }
            });
        }

        return $query;
    }

    private function handleFieldSearch($query, $field, $searchType, $searchValue)
    {
        switch ($searchType) {
            case 'includes':
                $query->where($field, 'LIKE', "%{$searchValue}%");
                break;
            case 'not_includes':
                $query->where($field, 'NOT LIKE', "%{$searchValue}%");
                break;
            case 'equals':
                $query->where($field, '=', $searchValue);
                break;
            case 'not_equals':
                $query->where($field, '!=', $searchValue);
                break;
            case 'empty':
                $query->whereNull($field)->orWhere($field, '');
                break;
            case 'not_empty':
                $query->whereNotNull($field)->where($field, '!=', '');
                break;
        }
    }

    private function handleDateRangeSearch($query, $field, $dateRange)
    {
        if (isset($dateRange['search_type'])) {
            switch ($dateRange['search_type']) {
                case 'period':
                    if (!empty($dateRange['start'])) {
                        $query->whereDate($field, '>=', $dateRange['start']);
                    }
                    if (!empty($dateRange['end'])) {
                        $query->whereDate($field, '<=', $dateRange['end']);
                    }
                    break;
                
                case 'only_blank':
                    $query->whereNull($field);
                    break;
                
                case 'exclude_blanks':
                    $query->whereNotNull($field);
                    break;
            }
        }
    }

    private function handleNumberRangeSearch($query, $field, $range)
    {
        if (isset($range['min']) && $range['min'] !== '') {
            $query->where($field, '>=', $range['min']);
        }
        if (isset($range['max']) && $range['max'] !== '') {
            $query->where($field, '<=', $range['max']);
        }
    }

    private function processCondition($query, $condition)
    {
        if (!isset($condition['field']) || !isset($condition['search_type'])) {
            return;
        }

        switch ($condition['field']) {
            case 'email':
            case 'name':
                if (isset($condition['search_value'])) {
                    $query->where(function ($query) use ($condition) {
                        $this->handleFieldSearch(
                            $query,
                            $condition['field'],
                            $condition['search_type'],
                            $condition['search_value']
                        );
                    });
                }
                break;

            case 'created_at':
                $this->handleDateRangeSearch($query, 'created_at', $condition);
                break;

            case 'number_of_error':
                $this->handleNumberRangeSearch($query, 'number_of_error', $condition);
                break;
        }
    }
}
