<?php

use yii\db\Query;
use app\models\User;
use yii\db\Migration;
use yii\helpers\ArrayHelper;
use app\models\AuthAssignment;
use app\models\Status;

/**
 * Class m230125_143114_update_permissions
 */
class m230125_143114_update_permissions extends Migration
{
    /**
      * {@inheritdoc}
      */
    public function safeUp()
    {
        $usersNotExists = (new Query())
            ->select(['auth_assignment.*', 'usuario.*'])
            ->from('auth_assignment')
            ->join('LEFT JOIN', 'usuario', 'usuario.id = auth_assignment.user_id')
            ->where(['usuario.nome' => null])
            ->all();

        $deletedPermissionsUserNotExists = ArrayHelper::getColumn($usersNotExists, 'user_id');

        AuthAssignment::deleteAll(['user_id' => $deletedPermissionsUserNotExists]);

        $this->addColumn(
            'auth_assignment',
            'active',
            $this->integer()->notNull()->after('user_id')->defaultValue(Status::STATUS_SIM)
        );

        $usersAdministrators = AuthAssignment::find()->where(['item_name' => User::PERFIL_ADMINISTRADOR])->all();

        $userAdminsitratorIds = ArrayHelper::getColumn($usersAdministrators, 'user_id');

        foreach ($userAdminsitratorIds as $userAdminsitratorId) {
            $this->insert('auth_assignment', [
                'item_name' => User::PERFIL_OBSERVADOR,
                'user_id' => $userAdminsitratorId,
                'active' => Status::STATUS_NAO,
                'created_at' => time()
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $usersAdministrators = AuthAssignment::find()->where(['item_name' => User::PERFIL_ADMINISTRADOR])->all();

        $userAdminsitratorIds = ArrayHelper::getColumn($usersAdministrators, 'user_id');

        AuthAssignment::deleteAll(['user_id' => $userAdminsitratorIds, 'item_name' => User::PERFIL_OBSERVADOR]);

        $this->dropColumn('auth_assignment', 'active');
    }
}
