#!/bin/bash
apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install intl

