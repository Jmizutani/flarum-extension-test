<?php

use Flarum\Extend;
use Junya\UserProfile\Api\Controller\CreateUserProfileController;
use Junya\UserProfile\Api\Controller\ShowUserProfileController;
use Junya\UserProfile\Api\Controller\ListProfileFieldsController;
use Junya\UserProfile\Api\Controller\CreateProfileFieldController;
use Junya\UserProfile\Api\Controller\UpdateProfileFieldController;
use Junya\UserProfile\Api\Controller\DeleteProfileFieldController;
use Junya\UserProfile\Api\Controller\ListSocialLinksController;
use Junya\UserProfile\Api\Controller\CreateSocialLinkController;
use Junya\UserProfile\Api\Controller\UpdateSocialLinkController;
use Junya\UserProfile\Api\Controller\DeleteSocialLinkController;
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
        ->get('/profile-fields', 'profile-fields.index', ListProfileFieldsController::class)
        ->post('/profile-fields', 'profile-fields.create', CreateProfileFieldController::class)
        ->patch('/profile-fields/{id:[0-9]+}', 'profile-fields.update', UpdateProfileFieldController::class)
        ->delete('/profile-fields/{id:[0-9]+}', 'profile-fields.delete', DeleteProfileFieldController::class)
        ->get('/social-links', 'social-links.index', ListSocialLinksController::class)
        ->post('/social-links', 'social-links.create', CreateSocialLinkController::class)
        ->patch('/social-links/{id:[0-9]+}', 'social-links.update', UpdateSocialLinkController::class)
        ->delete('/social-links/{id:[0-9]+}', 'social-links.delete', DeleteSocialLinkController::class)
        ->post('/user-profiles', 'user-profiles.create', CreateUserProfileController::class)
        ->get('/user-profiles', 'user-profiles.show', ShowUserProfileController::class),
    
    (new Extend\Model('Flarum\User\User'))
        ->relationship('userProfile', function ($user) {
            return $user->hasOne(\Junya\UserProfile\Model\UserProfile::class, 'user_id');
        }),
];