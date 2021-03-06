FROM alpine:3.8

RUN apk add --update \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-soap \
    php7-pdo \
    php7-mysqli \
    php7-pdo_mysql \
    php7-xml \
    php7-sockets \
    php7-zlib \
    php7-phar \
    php7-zip \
    php7-ftp \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    make \
    curl

# Install Composer
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/* && \
    curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Copy files
ADD site.ini /etc/php7/php-fpm.d/
ADD site.ini /etc/php7/cli/conf.d/
ADD xdebug.ini  /etc/php7/conf.d/
ADD php.ini    /etc/php7/php.ini
ADD symfony.pool.conf /etc/php7/php-fpm.d/

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/application
EXPOSE 9000