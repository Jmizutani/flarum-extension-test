<?php

namespace Junya\FlarumUserProfile\Model;

use Flarum\Database\AbstractModel;

class ProfileField extends AbstractModel
{
    protected $table = 'profile_fields';
    
    protected $fillable = [
        'name',
        'label',
        'type',
        'required',
        'sort_order',
        'is_active'
    ];
    
    protected $casts = [
        'required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function values()
    {
        return $this->hasMany(ProfileFieldValue::class, 'field_id');
    }
}