<?php

namespace Junya\UserProfile\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class ProfileFieldValue extends AbstractModel
{
    protected $table = 'profile_field_values';
    
    protected $fillable = [
        'user_id',
        'field_id',
        'value'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function field()
    {
        return $this->belongsTo(ProfileField::class, 'field_id');
    }
}