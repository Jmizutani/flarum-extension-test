<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Model\SocialLink;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteSocialLinkController extends AbstractShowController
{
    public $serializer = \Junya\UserProfile\Api\Serializer\SocialLinkSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        $socialLink = SocialLink::findOrFail($id);
        
        $socialLink->delete();
        
        return $socialLink;
    }
}