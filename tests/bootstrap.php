<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// Recrée la base de test à partir des migrations avant chaque exécution.
// Chaque test tourne ensuite dans une transaction annulée (dama/doctrine-test-bundle).
foreach ([
    'doctrine:database:drop --force --if-exists',
    'doctrine:database:create',
    'doctrine:migrations:migrate --no-interaction',
] as $command) {
    passthru(sprintf('php %s/bin/console %s --env=test --quiet', escapeshellarg(dirname(__DIR__)), $command), $exitCode);
    if (0 !== $exitCode) {
        exit($exitCode);
    }
}
