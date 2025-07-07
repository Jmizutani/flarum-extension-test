<?php

namespace Junya\UserProfile\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Junya\UserProfile\Model\ProfileField;

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
            'placeholder' => $field->placeholder,
            'required' => $field->required,
            'sortOrder' => $field->sort_order,
            'isActive' => $field->is_active,
            'createdAt' => $field->created_at ? $this->formatDate($field->created_at) : null,
            'updatedAt' => $field->updated_at ? $this->formatDate($field->updated_at) : null
        ];
    }
}