<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
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
    public function __construct(Model $model) {
        $this->setModel();
    }
    /**
     * get all records
     * @return Collectionn
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find a record by its primary key.
     *
     * @param int|string $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * find a record by its primary ket or throw an exception
     * @param int|string $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model
    {
        return $this->findOrFail($id);
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
    public function update(array $attributes, int $id): Model
    {
        $record = $this->findOrFail($id);
        $record->update($attributes);

        return $record;
    }

    /**
     * Delete a record by ID.
     *
     * @param int|string $id
     * @return bool|null
     */
    public function delete(int $id): ?bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    /**
     * Paginate the given query.
     *
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function pagniate(int $limit = 10): LengthAwarePaginator
    {
        return $this->model->pagniate($limit);
    }

    /**
     * Specify Model class name
     * @return void
     */
    private function setModel(): void
    {
        $newModel = App::make($this->model);

        if (!($newModel instanceof Model)) {
            throw new \Exception("Class {$newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model}");
        }
        $this->model = $newModel;
    }

    /**
     * Execute a callback within a database transaction
     * This ensures data integrity: if an error occurs, changes are rolled back.
     *
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     */
    protected function runInTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();

            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error("Transaction failed in " . static::class, [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }
    }

}

