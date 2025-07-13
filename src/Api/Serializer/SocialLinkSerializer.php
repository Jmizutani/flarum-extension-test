<?php

namespace Junya\UserProfile\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Junya\UserProfile\Model\SocialLink;

class SocialLinkSerializer extends AbstractSerializer
{
    protected $type = 'social-links';
    
    protected function getDefaultAttributes($socialLink)
    {
        if (!($socialLink instanceof SocialLink)) {
            error_log('SocialLinkSerializer: Invalid model type');
            return [];
        }
        
        try {
            error_log('SocialLinkSerializer: Serializing social link ID=' . $socialLink->id);
            
            return [
                'name' => $socialLink->name,
                'label' => $socialLink->label,
                'iconUrl' => $socialLink->icon_url,
                'placeholder' => $socialLink->placeholder,
                'sortOrder' => $socialLink->sort_order,
                'isActive' => $socialLink->is_active,
                'createdAt' => $socialLink->created_at ? $this->formatDate($socialLink->created_at) : null,
                'updatedAt' => $socialLink->updated_at ? $this->formatDate($socialLink->updated_at) : null
            ];
        } catch (\Exception $e) {
            error_log('SocialLinkSerializer: Exception = ' . $e->getMessage());
            throw $e;
        }
    }
}