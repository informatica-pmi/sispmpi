<?php

namespace app\components\rbac;

use app\models\Status;
use yii\db\Query;
use yii\rbac\DbManager;
use yii\rbac\Assignment;

class DbManagerCustom extends DbManager
{
    /**
     * {@inheritdoc}
     */
    public function getAssignments($userId)
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where(['user_id' => (string) $userId])
            ->andWhere(['active' => Status::STATUS_SIM]);

        $assignments = [];
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }
}
