<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;

class SocialLink extends AbstractModel
{
    protected $table = 'social_links';
    
    public $timestamps = true;
    
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
    
    protected static function boot()
    {
        parent::boot();
        
        // icon_urlが必須であることを確認
        static::creating(function ($model) {
            if (empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required');
            }
        });
        
        static::updating(function ($model) {
            if (empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required');
            }
        });
    }
}