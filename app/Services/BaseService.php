<?php

namespace App\Services;

use App\Shared\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseService constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * get all records
     * @return Collectionn
     */
    public function getAll(array $column = ['*']): Collection
    {
        return $this->model->all($column);
    }

    /**
     * Find a record by its primary key.
     *
     * @param int|string $id
     * @return Model|null
     */
    public function find($id, array $column = ['*']): ?Model
    {
        return $this->model->find($id, $column);
    }

    /**
     * find a record by its primary ket or throw an exception
     * @param int|string $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id, array $column = ['*']): Model
    {
        return $this->model->findOrFail($id, $column);
    }

    /**
     * Create new record
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * update an existing record.
     *
     * @param int|string $id
     * @param array $attributes
     * @return Model
     */
    public function update(int|string $id, array $attributes): Model
    {
        $record = $this->findOrFail($id);
        $record->update($attributes);

        return $record->refresh();
    }

    /**
     * Delete a record by ID.
     *
     * @param int|string $id
     * @return bool|null
     */
    public function delete(int|string $id): ?bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    /**
     * @param int $limit
     * @param array $column
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit = 10, array $column = ['*']): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($limit, $column);
    }

    public function restore(int|string $id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        return $record->restore();
    }

    /**
     * Execute a callback within a database transaction
     * This ensures data integrity: if an error occurs, changes are rolled back.
     *
     * @param callable $callback
     * @return mixed
     * @throws \Throwable
     */
    protected function runInTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();

            return $result;
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error("Transaction failed in " . static::class, [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'user_id' => Auth::id() ?? 'Guest'
            ]);
            throw $exception;
        }
    }

    protected function applyAdvancedFilter(
        Builder     $query,
        QueryFilter $filter,
        array       $searchableColumns = [],
        array       $sortableColumns = ['id', 'created_at']
    ): void
    {
        // 1. global search
        if (!empty($filter->keyword) && !empty($searchableColumns)) {
            $keywords = preg_split("/\s+/", trim($filter->keyword));
            $query->where(function ($query) use ($searchableColumns, $keywords) {
                foreach ($keywords as $keyword) {
                    $query->where(function ($query) use ($searchableColumns, $keyword) {
                       foreach ($searchableColumns as $column) {
                           $query->orWhere($column, 'LIKE', "%{$keyword}%");
                       }
                    });
                }
            });
        }
        // 2. exact match
        foreach ($filter->exact as $column => $value) {
            $query->where($column, $value);
        }
        // 3. ranges (from-to)
        foreach ($filter->range as $column => $range) {
            if (!empty($range['from'])) {
                $query->where($column, '>=', $range['from']);
            }
            if (!empty($range['to'])) {
                $query->where($column, '<=', $range['to']);
            }
        }
        // 4. dynamic sorting
        if (!empty($filter->sortBy) && in_array($filter->sortBy, $sortableColumns)) {
            $query->orderBy($filter->sortBy, $filter->sortDir);
        }
    }
}

