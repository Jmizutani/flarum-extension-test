<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Junya\UserProfile\Api\Serializer\ProfileFieldSerializer;
use Junya\UserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListProfileFieldsController extends AbstractListController
{
    public $serializer = ProfileFieldSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertAdmin();
        
        return ProfileField::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}