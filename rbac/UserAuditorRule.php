<?php

namespace app\rbac;

use yii\rbac\Rule;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * Checks if authorID matches user passed via params
 */
class UserAuditorRule extends Rule
{
    public $name = 'isUserAuditor';

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
            !ArrayHelper::isIn(
                $params['post']->authAssignment->item_name,
                [User::PERFIL_ADMINISTRADOR, User::PERFIL_TI, User::PERFIL_AUDITOR]
            ) :
            false;
    }
}
