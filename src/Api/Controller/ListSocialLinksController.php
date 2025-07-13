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
        try {
            error_log('ListSocialLinksController: Starting');
            
            $actor = $request->getAttribute('actor');
            error_log('ListSocialLinksController: Actor obtained');
            
            $actor->assertAdmin();
            error_log('ListSocialLinksController: Admin check passed');
            
            $socialLinks = SocialLink::orderBy('sort_order')->get();
            error_log('ListSocialLinksController: Found ' . $socialLinks->count() . ' social links');
            
            return $socialLinks;
            
        } catch (\Exception $e) {
            error_log('ListSocialLinksController: Exception = ' . $e->getMessage());
            error_log('ListSocialLinksController: Stack trace = ' . $e->getTraceAsString());
            throw $e;
        }
    }
}