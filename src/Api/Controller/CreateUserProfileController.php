<?php

namespace Junya\FlarumUserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\FlarumUserProfile\Api\Serializer\UserProfileSerializer;
use Junya\FlarumUserProfile\Model\UserProfile;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateUserProfileController extends AbstractCreateController
{
    public $serializer = UserProfileSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        $userId = $data['userId'] ?? null;
        
        if (!$userId || ($actor->id != $userId && !$actor->isAdmin())) {
            throw new PermissionDeniedException();
        }
        
        $profile = UserProfile::where('user_id', $userId)->first();
        
        if ($profile) {
            $profile->update([
                'introduction' => $data['introduction'] ?? null,
                'childcare_situation' => $data['childcareSituation'] ?? null,
                'care_situation' => $data['careSituation'] ?? null,
                'facebook_url' => $data['facebookUrl'] ?? null,
                'x_url' => $data['xUrl'] ?? null,
                'instagram_url' => $data['instagramUrl'] ?? null,
                'is_visible' => $data['isVisible'] ?? true
            ]);
        } else {
            $profile = UserProfile::create([
                'user_id' => $userId,
                'introduction' => $data['introduction'] ?? null,
                'childcare_situation' => $data['childcareSituation'] ?? null,
                'care_situation' => $data['careSituation'] ?? null,
                'facebook_url' => $data['facebookUrl'] ?? null,
                'x_url' => $data['xUrl'] ?? null,
                'instagram_url' => $data['instagramUrl'] ?? null,
                'is_visible' => $data['isVisible'] ?? true
            ]);
        }
        
        return $profile;
    }
}