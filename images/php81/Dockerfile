FROM composer:latest AS composer
FROM laradock/php-fpm:latest-8.1

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG USER_ID
ARG GROUP_ID

ADD ./php.ini /usr/local/etc/php/php.ini



# /home/alex22/dev/xd/images/php72/php.ini
COPY ./php.ini /usr/local/etc/php/php.ini

# RUN apk --update add curl \
#     && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install INTL
RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

RUN docker-php-ext-install exif

USER root

# Tip from https://github.com/chrootLogin/docker-nextcloud/issues/3#issuecomment-271626117
# RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN  usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

# RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

# WORKDIR /var/www

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"

CMD ["php-fpm"]

EXPOSE 9000