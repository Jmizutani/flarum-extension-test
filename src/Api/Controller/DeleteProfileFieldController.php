<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteProfileFieldController extends AbstractShowController
{
    public $serializer = \Junya\UserProfile\Api\Serializer\ProfileFieldSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        
        $field = ProfileField::findOrFail($id);
        $field->delete();
        
        return $field;
    }
}