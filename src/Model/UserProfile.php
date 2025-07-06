<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class UserProfile extends AbstractModel
{
    protected $table = 'user_profiles';
    
    protected $fillable = [
        'user_id',
        'is_visible'
    ];
    
    protected $casts = [
        'is_visible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fieldValues()
    {
        return $this->hasMany(ProfileFieldValue::class, 'user_id');
    }
    
    public function socialLinks()
    {
        return $this->hasMany(UserSocialLink::class, 'user_id');
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
        $field = \Junya\UserProfile\Model\ProfileField::where('name', $fieldName)->first();
        
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
            \Junya\UserProfile\Model\ProfileFieldValue::create([
                'user_id' => $this->user_id,
                'field_id' => $field->id,
                'value' => $value
            ]);
        }
    }
    
    public function getSocialLinkValue($socialLinkName)
    {
        $userSocialLink = $this->socialLinks()
            ->whereHas('socialLink', function ($query) use ($socialLinkName) {
                $query->where('name', $socialLinkName);
            })
            ->first();
            
        return $userSocialLink ? $userSocialLink->value : null;
    }
    
    public function setSocialLinkValue($socialLinkName, $value)
    {
        $socialLink = SocialLink::where('name', $socialLinkName)->first();
        
        if (!$socialLink) {
            return;
        }
        
        $userSocialLink = $this->socialLinks()
            ->where('social_link_id', $socialLink->id)
            ->first();
            
        if ($userSocialLink) {
            if ($value === null || $value === '') {
                $userSocialLink->delete();
            } else {
                $userSocialLink->update(['value' => $value]);
            }
        } else if ($value !== null && $value !== '') {
            UserSocialLink::create([
                'user_id' => $this->user_id,
                'social_link_id' => $socialLink->id,
                'value' => $value
            ]);
        }
    }
}