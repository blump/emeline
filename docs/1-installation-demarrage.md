# Installation et demarrage du projet

Ce document regroupe les operations necessaires pour installer, configurer et lancer l'application MediaTek Formation en local.

## 1. Prerequis

Installer les outils suivants :

- PHP compatible avec le projet ;
- Composer ;
- MySQL ou MariaDB ;
- un serveur web local : Symfony CLI, WampServer, Apache ou Nginx ;
- Git si le projet est recupere depuis un depot.

Le projet est une application Symfony 6.4. Il doit etre servi depuis le dossier `public`.

## 2. Recuperation du projet

Depuis le dossier de travail local :

```bash
git clone <url-du-depot> mediatekformation
cd mediatekformation
```

Si le projet est fourni sous forme d'archive, extraire l'archive puis se placer dans le dossier du projet.

## 3. Installation des dependances

Installer les dependances PHP :

```bash
composer install
```

Cette commande recree le dossier `vendor/`, qui n'est pas a modifier manuellement.

## 4. Configuration Doctrine et MySQL

Doctrine utilise la variable `DATABASE_URL` pour se connecter a MySQL.

La configuration peut etre placee dans `.env`, mais il est preferable d'utiliser `.env.local` pour les parametres propres au poste de developpement.

Exemple pour WampServer avec MySQL sur le port standard `3306`, utilisateur `root` sans mot de passe :

```dotenv
DATABASE_URL="mysql://root:@127.0.0.1:3306/mediatekformation?serverVersion=8.0&charset=utf8mb4"
```

Exemple pour un MySQL expose sur le port `8889` avec le mot de passe `root` :

```dotenv
DATABASE_URL="mysql://root:root@127.0.0.1:8889/mediatekformation?serverVersion=8.0&charset=utf8mb4"
```

Format general :

```text
mysql://utilisateur:mot_de_passe@hote:port/nom_base?serverVersion=version_mysql&charset=utf8mb4
```

## 5. Creation de la base de donnees

Creation de la base `mediatekformation` via Symfony/Doctrine :

```bash
php bin/console doctrine:database:create
```

Si la base existe deja, Doctrine peut afficher une erreur. Dans ce cas, verifier la base existante avec phpMyAdmin ou le client MySQL.

## 6. Migrations Doctrine

Executer les migrations existantes :

```bash
php bin/console doctrine:migrations:migrate
```

Lorsqu'une entite Doctrine est modifiee, creer une nouvelle migration :

```bash
php bin/console make:migration
```

Puis l'executer :

```bash
php bin/console doctrine:migrations:migrate
```

## 7. Import des donnees SQL

Le fichier `mediatekformation.sql` est present a la racine du projet. Il contient la structure et les donnees initiales.

Il contient deja :

- `CREATE DATABASE IF NOT EXISTS mediatekformation` ;
- `USE mediatekformation`.

Import avec le client MySQL :

```bash
mysql -u root -p mediatekformation < mediatekformation.sql
```

Si l'utilisateur `root` n'a pas de mot de passe :

```bash
mysql -u root mediatekformation < mediatekformation.sql
```

Avec un port specifique, par exemple `8889` :

```bash
mysql -h 127.0.0.1 -P 8889 -u root -p mediatekformation < mediatekformation.sql
```

Import via phpMyAdmin :

1. Ouvrir phpMyAdmin.
2. Creer la base `mediatekformation` si elle n'existe pas.
3. Selectionner la base.
4. Aller dans l'onglet `Importer`.
5. Choisir le fichier `mediatekformation.sql`.
6. Lancer l'import.

## 8. Demarrage avec Symfony CLI

Si Symfony CLI est installe, lancer :

```bash
symfony server:start
```

L'application est disponible sur l'URL affichee par Symfony CLI, generalement :

```text
https://127.0.0.1:8000
```

ou :

```text
http://127.0.0.1:8000
```

## 9. Demarrage avec WampServer

WampServer est une plateforme de developpement Windows qui fournit Apache, PHP, MySQL/MariaDB et phpMyAdmin.

Telechargement officiel : [https://www.wampserver.com/](https://www.wampserver.com/).

Installation type :

1. Installer WampServer.
2. Demarrer les services Apache et MySQL depuis l'icone WampServer.
3. Copier le projet dans `C:\wamp64\www\mediatekformation`.
4. Ouvrir un terminal dans ce dossier.
5. Executer `composer install`.
6. Configurer `DATABASE_URL` dans `.env`.
7. Creer la base, executer les migrations ou importer `mediatekformation.sql`.
8. Configurer un VirtualHost Apache dont le `DocumentRoot` pointe vers `public`.

Symfony doit etre servi depuis le dossier `public`, jamais depuis la racine du projet. Cela evite d'exposer `.env`, `src`, `config`, `vendor` ou les fichiers SQL.

## 10. Configuration Apache pour Symfony

Exemple de VirtualHost Apache pour WampServer :

```apache
<VirtualHost *:80>
    ServerName mediatekformation.local
    DocumentRoot "c:/wamp64/www/mediatekformation/public"

    <Directory "c:/wamp64/www/mediatekformation/public">
        AllowOverride None
        Require all granted
        FallbackResource /index.php
    </Directory>

    ErrorLog "logs/mediatekformation-error.log"
    CustomLog "logs/mediatekformation-access.log" common
</VirtualHost>
```

Etapes complementaires :

1. Activer les VirtualHosts dans Apache si necessaire.
2. Activer le module `rewrite_module` si une configuration `.htaccess` est utilisee.
3. Ajouter cette ligne dans le fichier `hosts` de Windows, ouvert en administrateur :

```text
127.0.0.1 mediatekformation.local
```

4. Redemarrer les services WampServer.
5. Ouvrir `http://mediatekformation.local`.

Si `FallbackResource` n'est pas disponible, utiliser une reecriture vers `index.php` :

```apache
<Directory "c:/wamp64/www/mediatekformation/public">
    AllowOverride All
    Require all granted
</Directory>
```

Puis ajouter un fichier `.htaccess` dans `public/` ou installer `symfony/apache-pack` si le projet doit gerer automatiquement cette configuration.

## 11. Configuration Nginx pour Symfony

Exemple de serveur Nginx :

```nginx
server {
    listen 80;
    server_name mediatekformation.local;
    root /var/www/mediatekformation/public;

    index index.php;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/mediatekformation_error.log;
    access_log /var/log/nginx/mediatekformation_access.log;
}
```

Points a adapter selon l'environnement :

- `root` doit pointer vers le dossier `public`.
- `fastcgi_pass` doit correspondre au service PHP-FPM reel.
- `server_name` doit etre associe a `127.0.0.1` dans le fichier `hosts` local.

## 12. Verification

Apres installation et demarrage :

1. Ouvrir la page d'accueil.
2. Verifier que les formations et playlists s'affichent.
3. Tester la connexion admin avec l'utilisateur configure dans `config/packages/security.yaml`.
4. Lancer les tests si l'environnement de test est configure :

```bash
vendor/bin/phpunit
```

