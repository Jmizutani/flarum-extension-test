<?php

namespace Junya\FlarumUserProfile\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Junya\FlarumUserProfile\Model\ProfileField;

class ProfileFieldSerializer extends AbstractSerializer
{
    protected $type = 'profile-fields';
    
    protected function getDefaultAttributes($field)
    {
        if (!($field instanceof ProfileField)) {
            return [];
        }
        
        return [
            'name' => $field->name,
            'label' => $field->label,
            'type' => $field->type,
            'required' => $field->required,
            'sortOrder' => $field->sort_order,
            'isActive' => $field->is_active,
            'createdAt' => $this->formatDate($field->created_at),
            'updatedAt' => $this->formatDate($field->updated_at)
        ];
    }
}