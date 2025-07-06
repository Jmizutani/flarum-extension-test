<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\UserProfileSerializer;
use Junya\UserProfile\Model\UserProfile;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ShowUserProfileController extends AbstractShowController
{
    public $serializer = UserProfileSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $userId = $request->getQueryParams()['userId'] ?? null;
        
        if (!$userId) {
            throw new PermissionDeniedException();
        }
        
        $profile = UserProfile::where('user_id', $userId)->first();
        
        if (!$profile) {
            return null;
        }
        
        if (!$profile->is_visible && $actor->id != $userId && !$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        return $profile;
    }
}