<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;

class ProfileField extends AbstractModel
{
    protected $table = 'profile_fields';
    
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'label',
        'type',
        'placeholder',
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
    
    public static function boot()
    {
        parent::boot();
        
        // 必須フィールドの確認（作成・更新時）
        static::creating(function ($model) {
            if (empty($model->name)) {
                throw new \InvalidArgumentException('name is required for creating profile field');
            }
            if (empty($model->label)) {
                throw new \InvalidArgumentException('label is required for creating profile field');
            }
        });
        
        static::updating(function ($model) {
            // 更新時は変更されたフィールドのみチェック
            if ($model->isDirty('name') && empty($model->name)) {
                throw new \InvalidArgumentException('name is required for updating profile field');
            }
            if ($model->isDirty('label') && empty($model->label)) {
                throw new \InvalidArgumentException('label is required for updating profile field');
            }
        });
    }
    
    public function values()
    {
        return $this->hasMany(ProfileFieldValue::class, 'field_id');
    }
}