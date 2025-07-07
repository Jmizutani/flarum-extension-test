<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\ProfileFieldSerializer;
use Junya\UserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateProfileFieldController extends AbstractCreateController
{
    public $serializer = ProfileFieldSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        return ProfileField::create([
            'name' => $data['name'],
            'label' => $data['label'],
            'type' => $data['type'] ?? 'text',
            'placeholder' => $data['placeholder'] ?? '',
            'required' => $data['required'] ?? false,
            'sort_order' => $data['sortOrder'] ?? 0,
            'is_active' => $data['isActive'] ?? true
        ]);
    }
}