<?php

namespace App\Services;

use App\Models\UserProfile;
use App\Services\BaseService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UserProfileService extends BaseService
{
    public function __construct(UserProfile $userProfile) {
        parent::__construct($userProfile);
    }

    /**
     * @param $userId
     * @return UserProfile
     */
    public function getByUserId($userId):UserProfile
    {
        return $this->model->where('user_id', $userId)->firstOrCreate(['user_id' => $userId],
        [
            'first_name' => null,
            "last_name" => null,
            "avatar" => null,
        ]);
    }


    /**
     * @param $userId
     * @param array $data
     * @param UploadedFile|null $avatar
     * @return UserProfile
     */
    public function updateProfile($userId, array $data, ?UploadedFile $avatar = null): UserProfile
    {
        $profile = $this->getByUserId($userId);

        if ($avatar) {
            if ($profile->avater_publc_id) {
                try {
                    Cloudinary::destroy($profile->avatar_public_id);
                } catch (\Exception $exception) {
                    Log::error("Delete photo failed: " . $exception->getMessage());
                }
            }
            Log::info("Start uploading photo");
            $uploadedFile = Cloudinary::upload($avatar->getRealPath(), [
                'folder' => 'avatars',
                'transformation' => [
                    'width' => 300,
                    'height' => 300,
                    'gravity' => "face",
                    'crop' => 'fill'
                ]
            ]);
            Log::info("Upload photo: " . $uploadedFile->getRealPath());

            $data['avater'] = $uploadedFile->getSecurePath();
            $data['avatar_public_id'] = $uploadedFile->getPublicId();
        }
        $profile->update($data);
        return $profile->refresh();
    }
}
