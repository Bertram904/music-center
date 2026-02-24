<?php

namespace App\Http\Controllers;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Services\RoomService;

class RoomController extends Controller
{
    public function __construct(protected RoomService $roomService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $room = $this->roomService->getList();

        return $this->respondSuccess(RoomResource::collection($room));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request): Response
    {
        $room = $this->roomService->create($request->validated());

        return $this->respondCreated(new RoomResource($room));
    }

    /**
     * Display the specified resource.
     */
    public function show($id): Response
    {
        $room = $this->roomService->findOrFail($id);

        return $this->respondSuccess(new RoomResource($room));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, $id) : Response
    {
        $room = $this->roomService->update($id, $request->validated());

        return $this->respondSuccess(new RoomResource($room), 'updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) : Response
    {
        try {
            $this->roomService->delete($id);

            return $this->respondSuccess(null, 'deleted successfully.');
        } catch (\Exception $exception) {
            return $this->respondError(
                null,
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
