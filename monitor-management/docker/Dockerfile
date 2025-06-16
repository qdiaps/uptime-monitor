FROM php:8.4.8-alpine3.20

WORKDIR /var/www/html

RUN apk add --no-cache tzdata && \
    ln -snf /usr/share/zoneinfo/UTC /etc/localtime && echo UTC > /etc/timezone

RUN apk add --no-cache python3 supervisor vim nodejs npm

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer xdebug pdo_pgsql pgsql rdkafka redis

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN adduser -S 1000 -G users -s /bin/sh -u 1000
USER 1000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
