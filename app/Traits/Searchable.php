<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

trait Searchable
{
    /**
     * Apply search query to the model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $searchTerm
     * @param array|null $searchableColumns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm = null, ?array $searchableColumns = null): Builder
    {

        if (empty($searchTerm)) {
            $searchTerm = Request::get('search');
        }

        if (empty($searchableColumns)) {
            $searchableColumns = $this->searchable ?? [];
        }

        if (empty($searchTerm) || empty($searchableColumns)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                $q->orWhere($column, 'ILIKE', "%{$searchTerm}%");


            }
        });
    }
}
