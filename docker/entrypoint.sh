#!/bin/sh
set -e

# Uniquement pour le serveur web : les commandes ponctuelles
# (`docker compose exec php bin/console ...`) passent directement.
if [ "$1" = 'frankenphp' ]; then
    # Réinstalle seulement si vendor/ est absent ou si composer.lock a changé
    if [ ! -f vendor/.install-stamp ] || [ composer.lock -nt vendor/.install-stamp ]; then
        echo "→ Installation des dépendances Composer..."
        composer install --prefer-dist --no-progress --no-interaction
        touch vendor/.install-stamp
    fi

    echo "→ Attente de la base de données..."
    until php bin/console dbal:run-sql -q "SELECT 1" >/dev/null 2>&1; do
        sleep 1
    done

    echo "→ Migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

    # Jeu de données de démo au premier démarrage (base vide)
    if [ "$APP_ENV" != 'prod' ] && [ "$(php bin/console dbal:run-sql 'SELECT COUNT(*) AS n FROM user' --force-fetch 2>/dev/null | grep -Eo '[0-9]+' | tail -1)" = "0" ]; then
        echo "→ Chargement des fixtures..."
        php bin/console doctrine:fixtures:load --no-interaction
    fi

    echo "→ Application disponible sur http://localhost:${APP_PORT:-8000}"
fi

exec docker-php-entrypoint "$@"
