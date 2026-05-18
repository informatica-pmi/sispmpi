FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Copia o script de inicialização para dentro do container
COPY start.sh /usr/local/bin/start.sh

# Dá permissão de execução para o script
RUN chmod +x /usr/local/bin/start.sh

# Define o script como o comando principal do container
CMD ["/usr/local/bin/start.sh"]