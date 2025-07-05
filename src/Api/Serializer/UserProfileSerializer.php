<?php

namespace Junya\FlarumUserProfile\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Junya\FlarumUserProfile\Model\UserProfile;

class UserProfileSerializer extends AbstractSerializer
{
    protected $type = 'user-profiles';
    
    protected function getDefaultAttributes($userProfile)
    {
        if (!($userProfile instanceof UserProfile)) {
            return [];
        }
        
        return [
            'userId' => $userProfile->user_id,
            'introduction' => $userProfile->introduction,
            'childcareSituation' => $userProfile->childcare_situation,
            'careSituation' => $userProfile->care_situation,
            'facebookUrl' => $userProfile->facebook_url,
            'xUrl' => $userProfile->x_url,
            'instagramUrl' => $userProfile->instagram_url,
            'isVisible' => $userProfile->is_visible,
            'createdAt' => $this->formatDate($userProfile->created_at),
            'updatedAt' => $this->formatDate($userProfile->updated_at)
        ];
    }
    
    protected function user($userProfile)
    {
        return $this->hasOne($userProfile, UserSerializer::class);
    }
}