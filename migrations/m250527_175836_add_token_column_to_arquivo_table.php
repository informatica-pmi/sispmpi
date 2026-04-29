<?php

use yii\db\Migration;
use yii\db\Query;

class m250527_175836_add_token_column_to_arquivo_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('arquivo', 'token', $this->string()->null());

        $arquivos = (new Query())
            ->from('arquivo')
            ->all();

        $tokens = [];

        foreach ($arquivos as $arquivo) {
            do {
                $token = Yii::$app->security->generateRandomString();

                $exists = in_array($token, $tokens);
            } while ($exists);

            $tokens[$arquivo['id']] = $token;

            $this->update('arquivo', ['token' => $token], ['id' => $arquivo['id']]);
        }

        $this->alterColumn('arquivo', 'token', $this->string()->notNull()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn('arquivo', 'token');
    }
}
