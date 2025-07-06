<?php

namespace Junya\FlarumUserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Junya\FlarumUserProfile\Api\Serializer\ProfileFieldSerializer;
use Junya\FlarumUserProfile\Model\ProfileField;
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