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
    
    public static function boot()
    {
        parent::boot();
        
        // icon_urlが必須であることを確認（作成・更新時のみ）
        static::creating(function ($model) {
            if (empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required for creating social link');
            }
        });
        
        static::updating(function ($model) {
            // 更新時は変更されたフィールドのみチェック
            if ($model->isDirty('icon_url') && empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required for updating social link');
            }
        });
    }
}