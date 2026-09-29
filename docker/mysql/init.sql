-- Exécuté uniquement à la création du volume MySQL
CREATE DATABASE IF NOT EXISTS bddautoecoleweb_test;
GRANT ALL PRIVILEGES ON bddautoecoleweb_test.* TO 'app'@'%';
