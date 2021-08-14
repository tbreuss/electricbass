FROM phpearth/php:7.3-apache

RUN apk add --no-cache \
    php7.3-pdo \
    php7.3-pdo_mysql \
    php7-pear \
    php7-dev \
    gcc \
    musl-dev make \
    php7-xdebug

RUN pecl install xdebug

# see https://gist.github.com/csgruenebe/3f5bdccfd0e0a8ca4391f5ecbd316c26
RUN sed -i -e 's/AllowOverride\s*None/AllowOverride All/ig' /etc/apache2/httpd.conf && \
    sed -i -e 's/\/var\/www\/localhost\/htdocs/\/app\/web/g' /etc/apache2/httpd.conf && \
    sed -i -e 's/#LoadModule\s*rewrite_module/LoadModule rewrite_module/gi' /etc/apache2/httpd.conf && \
    echo 'zend_extension=/usr/lib/php/7.3/modules/xdebug.so' >> /etc/php/7.3/php.ini && \
    echo 'xdebug.mode=debug' >> /etc/php/7.3/php.ini && \
    echo 'xdebug.client_host=host.docker.internal' >> /etc/php/7.3/php.ini
