FROM php:8.2-apache

# Instala dependências do sistema e extensões PHP necessárias para o Yii2
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql

# Ativa o mod_rewrite do Apache (essencial para as URLs amigáveis do Yii2)
RUN a2enmod rewrite

# Define o DocumentRoot para a pasta /web, conforme exigido pelo sistema [cite: 193]
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala o Composer 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia os arquivos do projeto para o container
COPY . .

# Ajusta permissões para as pastas runtime e web/assets [cite: 116]
RUN chown -R www-data:www-data runtime web/assets
