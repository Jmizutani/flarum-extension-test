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
        
        return SocialLink::create([
            'name' => $data['name'],
            'label' => $data['label'],
            'icon_url' => $data['iconUrl'] ?? '',
            'url_pattern' => $data['urlPattern'] ?? '',
            'sort_order' => $data['sortOrder'] ?? 0,
            'is_active' => $data['isActive'] ?? true
        ]);
    }
}