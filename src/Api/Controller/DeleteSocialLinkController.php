<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Model\SocialLink;
use Psr\Http\Message\ServerRequestInterface;

class DeleteSocialLinkController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        $socialLink = SocialLink::findOrFail($id);
        
        $socialLink->delete();
    }
}