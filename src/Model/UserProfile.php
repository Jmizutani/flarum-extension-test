<?php

namespace Junya\FlarumUserProfile\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class UserProfile extends AbstractModel
{
    protected $table = 'user_profiles';
    
    protected $fillable = [
        'user_id',
        'introduction',
        'childcare_situation',
        'care_situation',
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
}