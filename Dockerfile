FROM php:8.2-apache

# Instala dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql mbstring zip intl

# Configura o Git para aceitar o diretório do projeto como seguro
RUN git config --global --add safe.directory /var/www/html

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Define o DocumentRoot para a pasta /web
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia os arquivos do projeto para o contêiner
COPY . .

# Instala todas as dependências, incluindo as de teste/desenvolvimento (Gii e Debug)
RUN composer update --no-interaction

# NOVO: Instala as dependências do Yii2 ignorando pacotes de desenvolvimento (como o Gii)
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões das pastas que o Yii2 precisa escrever
RUN chown -R www-data:www-data runtime web/assets

# NOVO: Copia o script de inicialização para automatizar o yii migrate
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# NOVO: Define o script como porta de entrada
ENTRYPOINT ["entrypoint.sh"]

# Mantém o Apache rodando em primeiro plano
CMD ["apache2-foreground"]
