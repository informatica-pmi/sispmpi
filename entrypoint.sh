#!/bin/bash
# A primeira linha (shebang) é obrigatória no Linux

echo "Iniciando a verificação de migrações do banco de dados..."

# Executa as migrações automaticamente sem pedir confirmação
YII_ENV=prod php yii migrate --interactive=0

echo "Limpando o cache do Yii 2..."
php yii cache/flush-all

echo "Iniciando o servidor da aplicação..."
# O comando abaixo passa o controle para o CMD definido no Dockerfile (ex: Apache ou PHP-FPM)
exec "$@"
