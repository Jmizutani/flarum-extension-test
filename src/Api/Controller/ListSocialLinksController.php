<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Junya\UserProfile\Api\Serializer\SocialLinkSerializer;
use Junya\UserProfile\Model\SocialLink;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListSocialLinksController extends AbstractListController
{
    public $serializer = SocialLinkSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertAdmin();
        
        return SocialLink::orderBy('sort_order')->get();
    }
}