#!/bin/bash

# === NOVO: Força o Apache a repassar as variáveis do Docker para o PHP ===
echo "PassEnv DB_HOST DB_NAME DB_USER DB_PASSWORD TINYMCE_KEY SMTP_HOST SMTP_PORTA SMTP_USUARIO SMTP_SENHA EMAIL_ENVIO NOME_REMETENTE" > /etc/apache2/conf-available/docker-env.conf
# =========================================================================

# 1. Verifica se a pasta vendor existe. Se não, roda o composer update.
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer..."
    composer update
fi

# 2. Aguarda o MySQL estar pronto
echo "Aguardando o banco de dados..."
sleep 10

# 3. Roda as migrations automaticamente.
echo "Rodando migrations do Yii2..."
php yii migrate --interactive=0

# 4. Inicia o servidor web Apache em primeiro plano
echo "Iniciando o Apache..."
apache2-foreground
