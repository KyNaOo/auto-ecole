FROM dunglas/frankenphp:1-php8.4

# Extensions PHP nécessaires à Symfony / Doctrine (MySQL)
RUN install-php-extensions \
        pdo_mysql \
        intl \
        opcache \
        zip \
        apcu

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini $PHP_INI_DIR/conf.d/zz-app.ini
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

# Utilisateur non-root avec le même UID/GID que l'hôte : les fichiers créés
# dans le projet monté (vendor/, var/, migrations...) restent modifiables.
ARG UID=1000
ARG GID=1000
RUN groupadd --gid ${GID} app \
    && useradd --uid ${UID} --gid ${GID} --create-home app \
    && chown -R app:app /data/caddy /config/caddy

USER app
WORKDIR /app

ENV SERVER_NAME=":8000" \
    COMPOSER_HOME=/home/app/.composer

EXPOSE 8000

ENTRYPOINT ["app-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
