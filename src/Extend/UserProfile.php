<?php

namespace Junya\UserProfile\Extend;

use Flarum\User\User;
use Junya\UserProfile\Model\UserProfile as UserProfileModel;

class UserProfile
{
    public static function addUserProfileRelation()
    {
        User::setRelation('userProfile', function ($user) {
            return $user->hasOne(UserProfileModel::class, 'user_id');
        });
    }
}