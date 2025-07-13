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
        
        // 必須フィールドの確認（作成・更新時）
        static::creating(function ($model) {
            if (empty($model->name)) {
                throw new \InvalidArgumentException('name is required for creating social link');
            }
            if (empty($model->label)) {
                throw new \InvalidArgumentException('label is required for creating social link');
            }
            if (empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required for creating social link');
            }
        });
        
        static::updating(function ($model) {
            // 更新時は変更されたフィールドのみチェック
            if ($model->isDirty('name') && empty($model->name)) {
                throw new \InvalidArgumentException('name is required for updating social link');
            }
            if ($model->isDirty('label') && empty($model->label)) {
                throw new \InvalidArgumentException('label is required for updating social link');
            }
            if ($model->isDirty('icon_url') && empty($model->icon_url)) {
                throw new \InvalidArgumentException('icon_url is required for updating social link');
            }
        });
    }
}