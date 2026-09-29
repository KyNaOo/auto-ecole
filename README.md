# Auto-École Web

Application web de gestion d'auto-école (projet BTS SIO 2023), mise à jour en 2026.

- **Élève** : s'inscrire, réserver une leçon, consulter son planning et ses statistiques
- **Moniteur** : ajouter ses licences, consulter son planning et ses statistiques
- **Admin** : gérer catégories, véhicules, licences et leçons ; créer des moniteurs ; statistiques globales

**Stack** : PHP 8.4 · Symfony 7.4 LTS · Doctrine ORM 3 · MySQL 8.4 · FrankenPHP · PHPUnit 11

---

## Prérequis

Uniquement **Docker** avec **Docker Compose** (v2). Rien d'autre à installer : PHP, Composer et MySQL
tournent dans les conteneurs.

## Démarrage

```bash
git clone git@github.com:ort-montreuil/BST-SIO-G5-2023-AutoEcole-Web.git
cd BST-SIO-G5-2023-AutoEcole-Web
docker compose up -d --build
docker compose logs -f php     # suivre le démarrage (Ctrl+C pour quitter)
```

Au premier lancement, le conteneur `php` installe les dépendances Composer, crée le schéma de la base
et charge des données de démonstration. C'est prêt quand le log affiche
`Application disponible sur http://localhost:8000` (1 à 2 minutes la première fois).

| Service         | URL                   | Rôle                                      |
|-----------------|-----------------------|-------------------------------------------|
| Application     | http://localhost:8000 | le site                                   |
| phpMyAdmin      | http://localhost:8080 | explorer la base (connecté en root)       |
| Mailpit         | http://localhost:8025 | voir les e-mails envoyés par l'appli      |

Aucun e-mail ne part réellement : ils sont tous capturés par Mailpit (ex. confirmation d'inscription).

### Comptes de démonstration

Mot de passe commun : `azerty123`

| Rôle     | E-mail                    |
|----------|---------------------------|
| Admin    | ethanbellaiche0@gmail.com |
| Moniteur | jacob@trabelsi.com        |
| Élève    | qinhao@wu.com             |

## Au quotidien

```bash
docker compose up -d              # démarrer
docker compose down               # arrêter (les données sont conservées)
docker compose down -v            # arrêter ET effacer la base (repart de zéro au prochain up)

docker compose exec php bin/console <commande>   # console Symfony
docker compose exec php composer <commande>      # Composer
docker compose exec php bin/console doctrine:fixtures:load -n   # réinitialiser les données de démo
```

Le code est monté dans le conteneur : toute modification de `src/` ou `templates/` est visible
immédiatement en rechargeant la page.

### Modifier la base de données

Après avoir modifié une entité dans `src/Entity/` :

```bash
docker compose exec php bin/console make:migration
docker compose exec php bin/console doctrine:migrations:migrate
```

## Tests

```bash
docker compose exec php bin/phpunit              # toute la suite
docker compose exec php bin/phpunit --testdox    # affichage lisible
docker compose exec php bin/phpunit tests/Controller/UserControllerTest.php   # un seul fichier
```

La base `bddautoecoleweb_test` est recréée à partir des migrations à chaque lancement, et chaque test
s'exécute dans une transaction annulée à la fin (`dama/doctrine-test-bundle`). Les tests sont donc
indépendants les uns des autres et ne touchent pas aux données de développement.

| Dossier              | Contenu                                                                    |
|----------------------|----------------------------------------------------------------------------|
| `tests/Entity`       | tests unitaires des entités (rôles, relations)                             |
| `tests/Repository`   | requêtes : détection de conflits, statistiques SQL                         |
| `tests/Controller`   | tests fonctionnels : connexion, droits d'accès, CRUD admin, parcours élève et moniteur, inscription |
| `tests/Support`      | `EntityFactoryTrait` pour créer des données de test                        |

## Structure du projet

```
src/
  Controller/     une classe par espace (User, Moniteur, Admin) + CRUD (Categorie, Vehicule, Licence, Lecon)
  Entity/         User, Lecon, Licence, Vehicule, Categorie
  Repository/     requêtes Doctrine et statistiques en SQL
  Form/           formulaires Symfony
  DataFixtures/   données de démonstration (Faker)
templates/        vues Twig, un dossier par contrôleur
migrations/       migrations Doctrine
docker/           entrypoint, php.ini, script d'initialisation MySQL
```

## Dépannage

- **Un port est déjà utilisé** : changez-le au lancement, par ex.
  `APP_PORT=8001 PMA_PORT=8081 MAILPIT_PORT=8026 docker compose up -d`
- **Fichiers créés par Docker appartenant à root (Linux)** : si votre UID n'est pas 1000, reconstruisez avec
  `UID=$(id -u) GID=$(id -g) docker compose up -d --build`
- **Les tests échouent avec « Access denied … bddautoecoleweb_test »** : la base MySQL a été créée par
  une version antérieure du projet. Lancez `docker compose down -v && docker compose up -d`.
- **Repartir complètement de zéro** : `docker compose down -v`, puis `docker compose up -d --build`.
