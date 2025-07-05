<?php

namespace Junya\FlarumUserProfile\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Junya\FlarumUserProfile\Model\ProfileField;
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
        
        $field = ProfileField::findOrFail($id);
        $field->delete();
    }
}