<?php

namespace App\Services;

use App\Models\Subject;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubjectService extends BaseService
{
    public function __construct(Subject $subject)
    {
        parent::__construct($subject);
    }
    public function getList(array $filters = [])
    {
        return $this->model
            ->when (isset($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', (bool)$filters['is_active']);
            })
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $query->where(function ($subQuery) use ($filters) {
                    $keyword = $filters['keyword'];
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate($filters['limit'] ?? 10);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function createSubject(array $data)
    {
        return DB::transaction(function () use ($data) {
           try {
               $data['slug'] = Str::slug($data['name']);

               if (empty($data['code'])) {
                   $data['code'] = strtoupper(Str::slug($data['name'], "_"));
               } else {
                   $data['code'] = strtoupper($data['code']);
               }

               $subject = $this->create($data);

               Log::info("Subject created by UserID " . auth() ->id(), [
                   'subject' => $subject->id,
                   'code' => $subject->code,
               ]);
               return $subject;
           }  catch (\Exception $e) {
               Log::error("Failed to create subject: " . $e->getMessage());
               throw $e;
           }
        });
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function updateSubject($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $subject = $this->model->findOrFail($id);

                if (isset($data['name'])) {
                    $data['slug'] = strtoupper(Str::slug($data['name']));
                }

                if (isset($data['code'])) {
                    $data['code'] = strtoupper($data['code']);
                }

                $subject->update($data);

                Log::info("Subject updated by UserID " . auth() ->id(), ['subject_id' => $id]);

                return $subject->refresh();
            } catch (\Exception $e) {
                Log::error("Failed to update subject: " . $e->getMessage());
                throw $e;
            }
        });
    }
}
