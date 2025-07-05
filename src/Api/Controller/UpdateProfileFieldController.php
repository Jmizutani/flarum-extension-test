<?php

namespace Junya\FlarumUserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\FlarumUserProfile\Api\Serializer\ProfileFieldSerializer;
use Junya\FlarumUserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateProfileFieldController extends AbstractShowController
{
    public $serializer = ProfileFieldSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        $field = ProfileField::findOrFail($id);
        
        $field->update([
            'name' => $data['name'] ?? $field->name,
            'label' => $data['label'] ?? $field->label,
            'type' => $data['type'] ?? $field->type,
            'required' => $data['required'] ?? $field->required,
            'sort_order' => $data['sortOrder'] ?? $field->sort_order,
            'is_active' => $data['isActive'] ?? $field->is_active
        ]);
        
        return $field;
    }
}