<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;

/**
 * Checks if authorID matches user passed via params
 */
class OrgaoRule extends Rule
{
    public $name = 'isOrgao';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $orgaoId = Yii::$app->session->get('observador_orgao_id') ?? User::getIdentidade('orgao_id');

        return isset($params['post']) ? $params['post']->orgao_id ==  $orgaoId : false;
    }
}
