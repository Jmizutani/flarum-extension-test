<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\SocialLinkSerializer;
use Junya\UserProfile\Model\SocialLink;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateSocialLinkController extends AbstractCreateController
{
    public $serializer = SocialLinkSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        // バリデーション
        if (empty($data['name'])) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['name' => ['名前は必須です。']]
            );
        }
        
        if (empty($data['label'])) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['label' => ['表示名は必須です。']]
            );
        }
        
        if (!isset($data['iconUrl']) || empty(trim($data['iconUrl']))) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['icon_url' => ['アイコンURLは必須です。']]
            );
        }
        
        if (!filter_var($data['iconUrl'], FILTER_VALIDATE_URL)) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['icon_url' => ['有効なURLを入力してください。']]
            );
        }
        
        $socialLink = new SocialLink([
            'name' => $data['name'],
            'label' => $data['label'],
            'icon_url' => $data['iconUrl'],
            'placeholder' => $data['placeholder'] ?? '',
            'sort_order' => $data['sortOrder'] ?? 0,
            'is_active' => $data['isActive'] ?? true
        ]);
        
        $socialLink->save();
        
        return $socialLink;
    }
}