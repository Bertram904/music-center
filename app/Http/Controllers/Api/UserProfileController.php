<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserProfileService $userProfileService
    ) {}

    public function me(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $cacheKey = "user_profile_{$userId}";
        $profile = Cache::remember($cacheKey, now()->addDay(), function () use ($userId) {
            return $this->userProfileService->getByUserId($userId);
        });

        return response()->json([
            'success' => true,
            'message' => 'Profile found, get successfully.',
            'data'    => new UserProfileResource($profile),
        ]);
    }

    /**
     * @param UpdateUserProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $avatar = $request->file('avatar');

        $data = $request->validated();

        $profile = $this->userProfileService->updateProfile($userId, $data, $avatar);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data'    => new UserProfileResource($profile),
        ]);
    }
}
