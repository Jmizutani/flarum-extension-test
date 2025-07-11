<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;

class SocialLink extends AbstractModel
{
    protected $table = 'social_links';
    
    protected $fillable = [
        'name',
        'label',
        'icon_url',
        'placeholder',
        'sort_order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}