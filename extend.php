<?php

use Flarum\Extend;
use Junya\FlarumUserProfile\Api\Controller\CreateUserProfileController;
use Junya\FlarumUserProfile\Api\Controller\ShowUserProfileController;
use Junya\FlarumUserProfile\Api\Controller\ListProfileFieldsController;
use Junya\FlarumUserProfile\Api\Controller\CreateProfileFieldController;
use Junya\FlarumUserProfile\Api\Controller\UpdateProfileFieldController;
use Junya\FlarumUserProfile\Api\Controller\DeleteProfileFieldController;
use Junya\FlarumUserProfile\Extend\UserProfile;

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
            return $user->hasOne(\Junya\FlarumUserProfile\Model\UserProfile::class, 'user_id');
        }),
];