<?php

namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\Historico;
use app\models\Status;
use app\models\User;

class AuditBehaviors extends Behavior
{
    public $relations = [];
    public $extraFields = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function afterSave($event)
    {
        $newAttributes = $this->owner->getAttributes();
        $oldAttributes = $event->changedAttributes;

        $id = $newAttributes['id'];
        $model = get_class($this->owner);

        if (!empty($this->extraFields)) {
            foreach ($this->extraFields as $extraField) {
                $newValue = $this->owner[$extraField];

                if (!is_null($newValue)) {
                    $newAttributes[$extraField] = $newValue;
                }
            }
        }

        if (!array_key_exists('saveAudit', $newAttributes)) {
            return;
        }

        if (!empty($this->relations)) {
            foreach ($this->relations as $relation) {
                if (isset($relation['name']) && isset($relation['key'])) {
                    $result = $model::findOne($id);
                    $nameRelation = $relation['name'];
                    $nameKey = $relation['key'];

                    $oldAttributes[$relation['field']] = implode(
                        ", ",
                        ArrayHelper::getColumn($result->$nameRelation, $nameKey)
                    );
                }
            }
        }

        foreach ($oldAttributes as $nameField => $value) {
            if ($nameField === 'id') {
                continue;
            }

            if (array_key_exists($nameField, $newAttributes)) {
                $isMultiple = strpos($nameField, 'Ids') !== false ? Status::STATUS_SIM : Status::STATUS_NAO;

                if (is_array($newAttributes[$nameField])) {
                    $newValue = implode(", ", $newAttributes[$nameField]);
                } else {
                    $newValue = (string) $newAttributes[$nameField];
                }

                if ($newValue != $value) {
                    $newHistorico = new Historico();
                    $newHistorico->action = Historico::ACTION_CREATE_UPDATE;
                    $newHistorico->model = substr(get_class($this->owner), strrpos(get_class($this->owner), '\\') + 1);
                    $newHistorico->id_registro = $id;
                    $newHistorico->campo = $nameField;
                    $newHistorico->antigo_valor = (string) $value;
                    $newHistorico->novo_valor = $newValue;

                    $justificativaField = "justificativa_{$nameField}";
                    $newHistorico->justificativa = $newAttributes[$justificativaField] ?? null;

                    $newHistorico->multiple = $isMultiple;
                    $newHistorico->usuario_id = User::getIdentidade('id');
                    $newHistorico->usuario_perfil = User::getPerfil();

                    $newHistorico->save();
                }
            }
        }

        return true;
    }
}
