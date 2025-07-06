<?php

namespace Junya\UserProfile\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Junya\UserProfile\Model\UserProfile;

class UserProfileSerializer extends AbstractSerializer
{
    protected $type = 'user-profiles';
    
    protected function getDefaultAttributes($userProfile)
    {
        if (!($userProfile instanceof UserProfile)) {
            return [];
        }
        
        $attributes = [
            'userId' => $userProfile->user_id,
            'facebookUrl' => $userProfile->facebook_url,
            'xUrl' => $userProfile->x_url,
            'instagramUrl' => $userProfile->instagram_url,
            'isVisible' => $userProfile->is_visible,
            'createdAt' => $this->formatDate($userProfile->created_at),
            'updatedAt' => $this->formatDate($userProfile->updated_at),
            'customFields' => []
        ];
        
        // カスタムフィールドの値を取得
        $userProfile->load('fieldValues.field');
        foreach ($userProfile->fieldValues as $fieldValue) {
            if ($fieldValue->field) {
                $attributes['customFields'][$fieldValue->field->name] = $fieldValue->value;
            }
        }
        
        return $attributes;
    }
    
    protected function user($userProfile)
    {
        return $this->hasOne($userProfile, UserSerializer::class);
    }
}