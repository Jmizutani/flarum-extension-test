<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Api\Serializer\ProfileFieldSerializer;
use Junya\UserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateProfileFieldController extends AbstractShowController
{
    public $serializer = ProfileFieldSerializer::class;
    
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // デバッグログ
        error_log('UpdateProfileFieldController called');
        error_log('Request method: ' . $request->getMethod());
        error_log('Request headers: ' . json_encode($request->getHeaders()));
        
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
            'placeholder' => $data['placeholder'] ?? $field->placeholder,
            'required' => $data['required'] ?? $field->required,
            'sort_order' => $data['sortOrder'] ?? $field->sort_order,
            'is_active' => $data['isActive'] ?? $field->is_active
        ]);
        
        return $field;
    }
}