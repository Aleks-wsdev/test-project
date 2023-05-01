FROM composer:latest AS composer
FROM laradock/php-fpm:latest-8.1

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG USER_ID
ARG GROUP_ID

ADD ./php.ini /usr/local/etc/php/php.ini

COPY ./php.ini /usr/local/etc/php/php.ini

# Install INTL
RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

RUN docker-php-ext-install exif

###########################################################################
# xDebug:
###########################################################################

ARG INSTALL_XDEBUG=false
ARG XDEBUG_PORT=9003

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
  if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ] || { [ $(php -r "echo PHP_MAJOR_VERSION;") = "7" ] && { [ $(php -r "echo PHP_MINOR_VERSION;") = "4" ] || [ $(php -r "echo PHP_MINOR_VERSION;") = "3" ] ;} ;}; then \
    if [ ${LARADOCK_PHP_VERSION} = "8.2" ]; then \
      pecl install xdebug-3.2.0; \
    else \
      pecl install xdebug-3.1.4; \
    fi; \
  else \
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
      pecl install xdebug-2.5.5; \
    else \
      if [ $(php -r "echo PHP_MINOR_VERSION;") = "0" ]; then \
        pecl install xdebug-2.9.0; \
      else \
        pecl install xdebug-2.9.8; \
      fi \
    fi \
  fi && \
  docker-php-ext-enable xdebug \
;fi

###########################################################################
# MC:
###########################################################################

RUN apt-get install -yqq mc 

##################################################################

USER root

RUN  usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"

CMD ["php-fpm"]

EXPOSE 9000