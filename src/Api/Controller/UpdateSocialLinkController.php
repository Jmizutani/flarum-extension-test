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
        try {
            error_log('UpdateSocialLinkController: Starting');
            
            $actor = RequestUtil::getActor($request);
            error_log('UpdateSocialLinkController: Actor obtained, isAdmin = ' . ($actor->isAdmin() ? 'true' : 'false'));
            
            if (!$actor->isAdmin()) {
                error_log('UpdateSocialLinkController: Permission denied');
                throw new PermissionDeniedException();
            }
            
            $id = $request->getAttribute('id');
            error_log('UpdateSocialLinkController: ID = ' . $id);
            
            $socialLink = SocialLink::findOrFail($id);
            error_log('UpdateSocialLinkController: SocialLink found, name = ' . $socialLink->name);
            
            $body = $request->getParsedBody();
            error_log('UpdateSocialLinkController: Parsed body = ' . json_encode($body));
            
            $data = $body['data']['attributes'] ?? [];
            error_log('UpdateSocialLinkController: Attributes = ' . json_encode($data));
            
            $socialLink->update([
                'name' => $data['name'] ?? $socialLink->name,
                'label' => $data['label'] ?? $socialLink->label,
                'icon_url' => $data['iconUrl'] ?? $socialLink->icon_url,
                'placeholder' => $data['placeholder'] ?? $socialLink->placeholder,
                'sort_order' => $data['sortOrder'] ?? $socialLink->sort_order,
                'is_active' => $data['isActive'] ?? $socialLink->is_active
            ]);
            
            error_log('UpdateSocialLinkController: Update completed successfully');
            error_log('UpdateSocialLinkController: Returning socialLink with ID = ' . $socialLink->id);
            error_log('UpdateSocialLinkController: SocialLink data = ' . json_encode($socialLink->toArray()));
            return $socialLink;
            
        } catch (\Exception $e) {
            error_log('UpdateSocialLinkController: Exception = ' . $e->getMessage());
            error_log('UpdateSocialLinkController: Stack trace = ' . $e->getTraceAsString());
            throw $e;
        }
    }
}