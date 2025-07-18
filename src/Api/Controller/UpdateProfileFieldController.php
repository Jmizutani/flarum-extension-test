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
        $actor = RequestUtil::getActor($request);
        
        if (!$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }
        
        $id = $request->getAttribute('id');
        
        // URLから直接IDを取得する（フォールバック）
        if (empty($id)) {
            $path = $request->getUri()->getPath();
            if (preg_match('/\/profile-fields\/(\d+)/', $path, $matches)) {
                $id = $matches[1];
            }
        }
        
        $field = ProfileField::findOrFail($id);
        
        $data = $request->getParsedBody()['data']['attributes'] ?? [];
        
        // バリデーション（更新時に新しい値が提供された場合）
        if (isset($data['name']) && empty(trim($data['name']))) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['name' => ['フィールド名は必須です。']]
            );
        }
        
        if (isset($data['label']) && empty(trim($data['label']))) {
            throw new \Illuminate\Validation\ValidationException(
                app('validator')->make([], []),
                ['label' => ['表示ラベルは必須です。']]
            );
        }
        
        $field->update([
            'name' => $data['name'] ?? $field->name,
            'label' => $data['label'] ?? $field->label,
            'type' => $data['type'] ?? $field->type,
            'placeholder' => $data['placeholder'] ?? $field->placeholder,
            'required' => $data['required'] ?? $field->required,
            'sort_order' => $data['sortOrder'] ?? $field->sort_order,
            'is_active' => $data['isActive'] ?? $field->is_active
        ]);
        
        $field->touch();
        
        return $field;
    }
}