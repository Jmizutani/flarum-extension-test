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
        
        // 非公開プロフィールでも200を返すが、データは制限する
        if (!$profile->is_visible && $actor->id != $userId && !$actor->isAdmin()) {
            // 非公開プロフィールの場合は空のプロフィールオブジェクトを返す
            $emptyProfile = new UserProfile();
            $emptyProfile->user_id = $userId;
            $emptyProfile->is_visible = false;
            // 他のフィールドは空のまま
            return $emptyProfile;
        }
        
        return $profile;
    }
}