<?php

namespace App\Services;

use App\Models\Shift;
use App\Shared\Filters\QueryFilter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShiftService extends BaseService
{
    /**
     * ShiftService constructor
     * Injects the Shift model into the BaseService
     * @param Shift $model
     */
    public function __construct(Shift $model) {
        parent::__construct($model);
    }

    /**
     * @param QueryFilter $filter
     * @return LengthAwarePaginator
     */
    public function getList(QueryFilter $filter): LengthAwarePaginator
    {
        $query = $this->model->query();

        $this->applyAdvancedFilter(
            $query,
            $filter,
            searchableColumns: ['name'],
            sortableColumns: [
                'id', 'name',
                'start_time', 'end_time',
                'created_at', 'updated_at'
            ]
        );

        $limit = (int) request('limit', 10);

        return $query->paginate($limit);
    }

    /**
     * create a new shift record safely within a database transaction
     *
     * @param array $data
     * @return Shift
     * @throws \Throwable
     */
    public function createShift(array $data): Shift
    {
        return $this->runInTransaction(function () use ($data) {
           $shift = $this->create($data);

           Log::info("Shift [{$shift->name}] created successfully.", [
               'shift_id' => $shift->id,
               'created_by' => Auth::id() ??  'System'
           ]);

           return $shift;
        });
    }

    /**
     * @param int|string $id
     * @param array $data
     * @return Shift
     * @throws \Throwable
     */
    public function updateShift(int|string $id, array $data): Shift
    {
        return $this->runInTransaction(function () use ($id, $data) {
           $shift = $this->update($id, $data);

           Log::info("Shift ID [{$id}] updated successfully.", [
               'shift_id' => $id,
               'updated_by' => Auth::id() ??  'System'
           ]);
           return $shift;
        });
    }

    public function deleteShift(int|string $id): bool
    {
        return $this->runInTransaction(function () use ($id) {
           $shift = $this->findOrFail($id);

           $shift->delete();
           Log::info("Shift ID [{$id}] deleted successfully.", [
               'shift_id' => $id,
               'deleted_by' => Auth::id() ??  'System'
           ]);
           return true;
        });
    }
}
