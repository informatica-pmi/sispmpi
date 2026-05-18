# Usa a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instala dependências do sistema e extensões do PHP necessárias para o Yii2
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip

# Habilita o mod_rewrite do Apache (essencial para URLs amigáveis do Yii2)
RUN a2enmod rewrite

# Instala o Composer globalmente copiando da imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho padrão do Apache
WORKDIR /var/www/html

# Ajusta permissões do usuário do Apache para evitar conflitos de leitura/escrita no WSL
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data