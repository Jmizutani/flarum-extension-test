<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\SocialLinkSerializer;
use Junya\UserProfile\Model\SocialLink;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateSocialLinkController extends AbstractShowController
{
    public $serializer = SocialLinkSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        
        // URLから直接IDを取得する（フォールバック）
        if (empty($id)) {
            $path = $request->getUri()->getPath();
            if (preg_match('/\/social-links\/(\d+)/', $path, $matches)) {
                $id = $matches[1];
            }
        }
        
        $socialLink = SocialLink::findOrFail($id);
        
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        // バリデーション（更新時に新しい値が提供された場合）
        if (isset($data['name']) && empty(trim($data['name']))) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['name' => ['名前は必須です。']]
            );
        }
        
        if (isset($data['label']) && empty(trim($data['label']))) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['label' => ['表示名は必須です。']]
            );
        }
        
        if (isset($data['iconUrl'])) {
            if (empty(trim($data['iconUrl']))) {
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
        }
        
        $socialLink->update([
            'name' => $data['name'] ?? $socialLink->name,
            'label' => $data['label'] ?? $socialLink->label,
            'icon_url' => $data['iconUrl'] ?? $socialLink->icon_url,
            'placeholder' => $data['placeholder'] ?? $socialLink->placeholder,
            'sort_order' => $data['sortOrder'] ?? $socialLink->sort_order,
            'is_active' => $data['isActive'] ?? $socialLink->is_active
        ]);
        
        $socialLink->touch();
        
        return $socialLink;
    }
}