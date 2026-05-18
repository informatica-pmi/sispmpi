#!/bin/bash

# 1. Verifica se a pasta vendor existe. Se não, roda o composer update.
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer..."
    composer update
fi

# 2. Aguarda o MySQL estar pronto (simples sleep para garantir que o banco subiu)
echo "Aguardando o banco de dados..."
sleep 10

# 3. Roda as migrations automaticamente. 
# O parâmetro --interactive=0 impede que o terminal peça para você digitar "yes"
echo "Rodando migrations do Yii2..."
php yii migrate --interactive=0

# 4. Inicia o servidor web Apache em primeiro plano
echo "Iniciando o Apache..."
apache2-foreground