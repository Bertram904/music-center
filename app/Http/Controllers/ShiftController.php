<?php

namespace App\Http\Controllers;

use App\Constants\ApiCodes;
use App\Http\Requests\Shift\StoreShiftRequest;
use App\Http\Requests\Shift\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;
use App\Services\ShiftService;
use App\Shared\Filters\QueryFilterFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class ShiftController extends Controller
{
    public function __construct(protected ShiftService $shiftService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // 1. transform raw Http request into a clean DTO
        $filter = QueryFilterFactory::fromRequest(
            $request,
            exactKeys: ['is_active'],
            rangeKeys: ['start_time', 'end_time'],
        );

        // 2.Pass DTO to Service layer
        $shifts = $this->shiftService->getList($filter);

        // 3. return formatted JSON response
        return $this->respondSuccess(ShiftResource::collection($shifts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftRequest $request): JsonResponse
    {
        $shift = $this->shiftService->createShift($request->validated());

        return $this->respondCreated(new ShiftResource($shift),
            __('Shift successfully created.')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int|string $id): JsonResponse
    {
        $shift = $this->shiftService->findOrFail($id);

        return $this->respondSuccess(new ShiftResource($shift));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int|string $id, UpdateShiftRequest $request): JsonResponse
    {
        $shift = $this->shiftService->updateShift($id, $request->validated());

        return $this->respondSuccess(new ShiftResource($shift),
            __('Shift successfully updated.')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $this->shiftService->deleteShift($id);

            return $this->respondSuccess(null, __('Shift successfully deleted.'));
        } catch (\Throwable $exception) {
            return $this->respondError(
                ApiCodes::HTTP_NOT_FOUND,
                \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST,
                $exception->getMessage(),
            );
        }
    }
}
