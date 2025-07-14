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
        // ソーシャルリンク定義は誰でも閲覧可能（プロフィール表示に必要）
        // 管理機能（作成・更新・削除）は別のコントローラーで管理者権限をチェック
        
        return SocialLink::orderBy('sort_order')->get();
    }
}