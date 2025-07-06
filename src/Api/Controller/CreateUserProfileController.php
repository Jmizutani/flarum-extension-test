<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\UserProfileSerializer;
use Junya\UserProfile\Model\UserProfile;
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
                'is_visible' => $data['isVisible'] ?? true
            ]);
        } else {
            $profile = UserProfile::create([
                'user_id' => $userId,
                'is_visible' => $data['isVisible'] ?? true
            ]);
        }
        
        // ソーシャルリンクの処理
        if (isset($data['facebookUrl'])) {
            $profile->setSocialLinkValue('facebook', $data['facebookUrl']);
        }
        if (isset($data['xUrl'])) {
            $profile->setSocialLinkValue('x', $data['xUrl']);
        }
        if (isset($data['instagramUrl'])) {
            $profile->setSocialLinkValue('instagram', $data['instagramUrl']);
        }
        
        // カスタムフィールドの処理
        if (isset($data['customFields'])) {
            foreach ($data['customFields'] as $fieldName => $value) {
                $profile->setFieldValue($fieldName, $value);
            }
        }
        
        return $profile;
    }
}