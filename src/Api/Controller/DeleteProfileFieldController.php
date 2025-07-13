<?php

namespace Junya\UserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\UserProfile\Model\ProfileField;
use Psr\Http\Message\ServerRequestInterface;

class DeleteProfileFieldController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
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
        $field->delete();
    }
}