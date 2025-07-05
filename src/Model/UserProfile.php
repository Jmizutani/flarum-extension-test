<?php

namespace Junya\FlarumUserProfile\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class UserProfile extends AbstractModel
{
    protected $table = 'user_profiles';
    
    protected $fillable = [
        'user_id',
        'facebook_url',
        'x_url',
        'instagram_url',
        'is_visible'
    ];
    
    protected $casts = [
        'is_visible' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fieldValues()
    {
        return $this->hasMany(ProfileFieldValue::class, 'user_id');
    }
    
    public function getFieldValue($fieldName)
    {
        $fieldValue = $this->fieldValues()
            ->whereHas('field', function ($query) use ($fieldName) {
                $query->where('name', $fieldName);
            })
            ->first();
            
        return $fieldValue ? $fieldValue->value : null;
    }
    
    public function setFieldValue($fieldName, $value)
    {
        $field = \Junya\FlarumUserProfile\Model\ProfileField::where('name', $fieldName)->first();
        
        if (!$field) {
            return;
        }
        
        $fieldValue = $this->fieldValues()
            ->where('field_id', $field->id)
            ->first();
            
        if ($fieldValue) {
            if ($value === null || $value === '') {
                $fieldValue->delete();
            } else {
                $fieldValue->update(['value' => $value]);
            }
        } else if ($value !== null && $value !== '') {
            \Junya\FlarumUserProfile\Model\ProfileFieldValue::create([
                'user_id' => $this->user_id,
                'field_id' => $field->id,
                'value' => $value
            ]);
        }
    }
}