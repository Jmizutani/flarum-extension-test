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
        $socialLink = SocialLink::findOrFail($id);
        
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        $socialLink->update([
            'name' => $data['name'] ?? $socialLink->name,
            'label' => $data['label'] ?? $socialLink->label,
            'icon_url' => $data['iconUrl'] ?? $socialLink->icon_url,
            'placeholder' => $data['placeholder'] ?? $socialLink->placeholder,
            'sort_order' => $data['sortOrder'] ?? $socialLink->sort_order,
            'is_active' => $data['isActive'] ?? $socialLink->is_active
        ]);
        
        return $socialLink;
    }
}