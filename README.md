# BST-SIO-G5-2023-AutoEcole-Web

#Description

Ce projet à été crée avec Symfony CLI 5.4 et php 8.0 avec les fonctionnalités suivantes :

-Page connexion / inscription
-Consulter son planning en tant qu'élève/moniteur
-S'incrire à une leçon en tant qu'elève
-Ajouter une licence en tant que moniteur
-Modifier son mot de passe et profil en tant qu'élève/moniteur
-Accéder au crud de toutes les entités via le rôle admin 

#Installation

Veuillez suivre les étapes :

1. Cloner le projet
`git clone git@github.com:ort-montreuil/BST-SIO-G5-2023-AutoEcole-Web.git`

2. Installer les dépendances via Composer :
`composer install`

3.Créer une nouvelle base de donnée, n'oubliez pas mettre à jour le fichier .env avec vos identifiants de base de donnée et de votre SGBD:
`DATABASE_URL="mysql://your_name:your_password@127.0.0.1:3306/db_name?serverVersion=your_SGBD&charset=utf8mb4"`

4.Lancer la migration de base de donnée
`php bin/console doctrine:migrations:migrate`

5.Remplissez la base de donnée à l'aide des fixtures 
`symfony console l : d :f`

6.Connectez vous !
-Admin, ethanbellaiche0@gmail.com
-Moniteur, jacob@trabelsi.com
-Elève, qinhao@wu.com

Ils possèdent tous le mot de passe, azerty123


