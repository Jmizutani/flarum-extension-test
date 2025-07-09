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
        try {
            error_log('UpdateProfileFieldController: Starting');
            
            $actor = RequestUtil::getActor($request);
            error_log('UpdateProfileFieldController: Actor obtained, isAdmin = ' . ($actor->isAdmin() ? 'true' : 'false'));
            
            if (!$actor->isAdmin()) {
                error_log('UpdateProfileFieldController: Permission denied');
                throw new PermissionDeniedException();
            }
            
            $id = $request->getAttribute('id');
            error_log('UpdateProfileFieldController: ID = ' . $id);
            error_log('UpdateProfileFieldController: Request URI = ' . $request->getUri()->getPath());
            error_log('UpdateProfileFieldController: Request attributes = ' . json_encode($request->getAttributes()));
            
            // URLから直接IDを取得する試行
            $path = $request->getUri()->getPath();
            if (preg_match('/\/profile-fields\/(\d+)/', $path, $matches)) {
                $id = $matches[1];
                error_log('UpdateProfileFieldController: ID extracted from URL = ' . $id);
            }
            
            $field = ProfileField::findOrFail($id);
            error_log('UpdateProfileFieldController: Field found, name = ' . $field->name);
            
            $body = $request->getParsedBody();
            error_log('UpdateProfileFieldController: Parsed body = ' . json_encode($body));
            
            $data = $body['data']['attributes'] ?? [];
            error_log('UpdateProfileFieldController: Attributes = ' . json_encode($data));
            
            $field->update([
                'name' => $data['name'] ?? $field->name,
                'label' => $data['label'] ?? $field->label,
                'type' => $data['type'] ?? $field->type,
                'placeholder' => $data['placeholder'] ?? $field->placeholder,
                'required' => $data['required'] ?? $field->required,
                'sort_order' => $data['sortOrder'] ?? $field->sort_order,
                'is_active' => $data['isActive'] ?? $field->is_active
            ]);
            
            error_log('UpdateProfileFieldController: Update completed successfully');
            error_log('UpdateProfileFieldController: Returning field with ID = ' . $field->id);
            error_log('UpdateProfileFieldController: Field data = ' . json_encode($field->toArray()));
            return $field;
            
        } catch (\Exception $e) {
            error_log('UpdateProfileFieldController: Exception = ' . $e->getMessage());
            error_log('UpdateProfileFieldController: Stack trace = ' . $e->getTraceAsString());
            throw $e;
        }
    }
}