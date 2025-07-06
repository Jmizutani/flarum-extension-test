<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class UserSocialLink extends AbstractModel
{
    protected $table = 'user_social_links';
    
    protected $fillable = [
        'user_id',
        'social_link_id',
        'value'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function socialLink()
    {
        return $this->belongsTo(SocialLink::class);
    }
}