<?php

namespace app\rbac;

use yii\rbac\Rule;
use app\models\User;
use app\models\Status;

/**
 * Checks if authorID matches user passed via params
 */
class OrgaoWithStatusRule extends Rule
{
    public $name = 'isOrgaoWithStatus';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ?
            $params['post']->orgao_id == User::getIdentidade('orgao_id') &&
            $params['post']->status != Status::PLANO_PUBLICADO && $params['post']->status != Status::PLANO_OBSOLETO :
            false;
    }
}
