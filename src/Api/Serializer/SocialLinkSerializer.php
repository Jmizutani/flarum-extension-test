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
            return [];
        }
        
        return [
            'name' => $socialLink->name,
            'label' => $socialLink->label,
            'iconUrl' => $socialLink->icon_url,
            'urlPattern' => $socialLink->url_pattern,
            'sortOrder' => $socialLink->sort_order,
            'isActive' => $socialLink->is_active,
            'createdAt' => $socialLink->created_at ? $this->formatDate($socialLink->created_at) : null,
            'updatedAt' => $socialLink->updated_at ? $this->formatDate($socialLink->updated_at) : null
        ];
    }
}