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
        // フィールド定義は誰でも閲覧可能（プロフィール表示に必要）
        // 管理機能（作成・更新・削除）は別のコントローラーで管理者権限をチェック
        
        return ProfileField::orderBy('sort_order')
            ->get();
    }
}