<?php

namespace App\Services;

use App\Models\Room;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoomService extends BaseService {
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function getList() {
        $query = $this->model->query();

        $this->addFilter($query);

        $limit = request('limit', 10);
        return $query->latest()->paginate($limit);
    }

    public function createRoom(array $data) {
        return $this->runInTransaction(function () use ($data) {
            $room = $this->create($data);

            Log::info("Room created by User " . (Auth::id() ?? 'System'), ['room_id' => $room->id]);
            return $room;
        }); 
    }

    public function updateRoom($id, array $data) {
        return $this->runInTransaction(function () use ($id, $data) {
            $room = $this->update($id, $data);

            Log::info("Room update by User: " . (Auth::id() ?? 'System'), ['room_id' => $id]);
        }); 
    }

    public function deleteRoom($id)
    {
        return $this->runInTransaction(function () use ($id) {
            $room = $this->findOrFail($id);
            $room->delete();

            // if ($room->shifts()->exists()) {
            //     throw new Exception("Could not delete rooms");
            // }
            
            Log::infor("Room deleted by User: " . (Auth::id() ?? 'System'), ['room_id' => $id]);

            return true;
        });
    }

    protected function addFilter($query): void
    {
        $keyword = request('keyword');
        $minCapacity = request('min_capacity');
        $isActive = request('is_active');

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($minCapacity) {
            $query->where('capacity', '>=', $minCapacity);
        }

        if ($isActive) {
            $query->where('is_active', (bool)$isActive);
        }
    }
}