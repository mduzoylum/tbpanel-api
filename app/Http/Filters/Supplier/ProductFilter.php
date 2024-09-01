<?php

namespace App\Http\Filters\Supplier;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected Request $request;

    protected array $allowedFilters = [
        'quantity_min', 'quantity_max', 'target_quantity_min', 'target_quantity_max',
        'buying_price_min', 'buying_price_max', 'list_price_min', 'list_price_max',
        'sale_price_min', 'sale_price_max', 'unit_id_min', 'unit_id_max'
    ];

    protected array $allowedSorts = [
        'id', 'name', 'price'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->applyFilters();
        $this->applySearch();
        $this->applySorting();

        return $this->builder;
    }

    protected function applySearch(): void
    {
        $search = $this->request->get('search');

        if ($search) {
            $this->builder->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
    }

    protected function applyFilters(): void
    {
        foreach ($this->getAllowedFilters() as $filter => $value) {
            if ($this->isFilterValueEmpty($value)) {
                continue;
            }

            if ($this->isRangeFilter($filter)) {
                $this->applyRangeFilter($filter, $value);
            } elseif ($this->isLikeFilter($filter)) {
                $this->applyLikeFilter($filter, $value);
            } else {
                $this->applyExactFilter($filter, $value);
            }
        }
    }

    protected function applySorting(): void
    {
        $sort = $this->request->get('sort');
        if ($sort && $this->isAllowedSort($sort)) {
            $direction = $this->getSortDirection($sort);
            $column = ltrim($sort, '-');

            $this->builder->orderBy($column, $direction);
        }
    }

    protected function getAllowedFilters(): array
    {
        $filters = $this->request->get('filters', []);

        return array_filter($filters, function ($value, $key) {
            return in_array($key, $this->allowedFilters);
        }, ARRAY_FILTER_USE_BOTH);
    }


    protected function isFilterValueEmpty($value): bool
    {
        return $value === 'undefined' || is_null($value) || $value === '';
    }

    protected function isRangeFilter(string $filter): bool
    {
        return str_ends_with($filter, '_min') || str_ends_with($filter, '_max');
    }

    protected function applyRangeFilter(string $filter, $value): void
    {
        $column = str_replace(['_min', '_max'], '', $filter);
        $operator = str_ends_with($filter, '_min') ? '>=' : '<=';
        $this->builder->where($column, $operator, $value);
    }

    protected function isLikeFilter(string $filter): bool
    {
        return str_contains($filter, '_like');
    }

    protected function applyLikeFilter(string $filter, $value): void
    {
        $column = str_replace('_like', '', $filter);
        $this->builder->where($column, 'like', '%' . $value . '%');
    }

    protected function applyExactFilter(string $filter, $value): void
    {
        $this->builder->where($filter, '=', $value);
    }

    protected function isAllowedSort(string $sort): bool
    {
        return in_array(ltrim($sort, '-'), $this->allowedSorts);
    }

    protected function getSortDirection(string $sort): string
    {
        return str_starts_with($sort, '-') ? 'desc' : 'asc';
    }
}
