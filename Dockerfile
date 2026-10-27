FROM iqionly/php-wce:8.4-apache AS development

LABEL org.opencontainers.image.authors="mochammadrizkyashyari@gmail.com"

USER root

ARG USER_ID=1000
ARG GROUP_ID=1000

# Caching Composer Installer Packages
VOLUME ["/root/.composer/cache"]

RUN userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data

WORKDIR /var/www

RUN rm -rdf /var/www/html

RUN install-php-extensions @fix_letsencrypt

RUN install-php-extensions @composer intl

COPY default.conf /etc/apache2/sites-available/000-default.conf

COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

COPY php.ini /usr/local/etc/php/php.ini

COPY docker-php-entrypoint.sh /usr/local/bin/docker-php-entrypoint

RUN chmod +x /usr/local/bin/docker-php-entrypoint

COPY .env.example /var/www/.env

USER www-data

EXPOSE 80 443

ENTRYPOINT ["docker-php-entrypoint"]
