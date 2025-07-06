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
            'facebookUrl' => $userProfile->getSocialLinkValue('facebook'),
            'xUrl' => $userProfile->getSocialLinkValue('x'),
            'instagramUrl' => $userProfile->getSocialLinkValue('instagram'),
            'isVisible' => $userProfile->is_visible,
            'createdAt' => $userProfile->created_at ? $this->formatDate($userProfile->created_at) : null,
            'updatedAt' => $userProfile->updated_at ? $this->formatDate($userProfile->updated_at) : null,
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