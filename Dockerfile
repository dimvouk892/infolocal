FROM php:8.4-fpm

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip intl \
    && echo "upload_max_filesize = 4M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 8M" >> /usr/local/etc/php/conf.d/uploads.ini

COPY php-fpm-listen.conf /usr/local/etc/php-fpm.d/zzz-listen.conf

WORKDIR /var/www

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
