<?php

use Flarum\Extend;
use Junya\UserProfile\Api\Controller\CreateUserProfileController;
use Junya\UserProfile\Api\Controller\ShowUserProfileController;
use Junya\UserProfile\Api\Controller\ListProfileFieldsController;
use Junya\UserProfile\Api\Controller\CreateProfileFieldController;
use Junya\UserProfile\Api\Controller\UpdateProfileFieldController;
use Junya\UserProfile\Api\Controller\DeleteProfileFieldController;
use Junya\UserProfile\Extend\UserProfile;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    
    new Extend\Locales(__DIR__.'/locale'),
    
    (new Extend\Routes('api'))
        ->post('/user-profiles', 'user-profiles.create', CreateUserProfileController::class)
        ->get('/user-profiles', 'user-profiles.show', ShowUserProfileController::class)
        ->get('/profile-fields', 'profile-fields.index', ListProfileFieldsController::class)
        ->post('/profile-fields', 'profile-fields.create', CreateProfileFieldController::class)
        ->patch('/profile-fields/{id}', 'profile-fields.update', UpdateProfileFieldController::class)
        ->delete('/profile-fields/{id}', 'profile-fields.delete', DeleteProfileFieldController::class),
    
    (new Extend\Model('Flarum\User\User'))
        ->relationship('userProfile', function ($user) {
            return $user->hasOne(\Junya\UserProfile\Model\UserProfile::class, 'user_id');
        }),
];