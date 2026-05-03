# Configuration Alwaysdata pour Symfony

Ce projet est une application Symfony 6.4 qui demande PHP 8.1 ou plus. Sur Alwaysdata, il faut configurer le site comme une application PHP et faire pointer la racine web vers le dossier `public/`.

## Site Alwaysdata

Dans l'administration Alwaysdata :

- aller dans `Web > Sites` ;
- creer ou modifier le site ;
- choisir le type `PHP` ;
- choisir PHP `8.2`, `8.3` ou plus recent ;
- definir le repertoire racine sur :

```text
/home/<compte>/www/public
```

Dans l'interface Alwaysdata, si le site demande un chemin relatif au compte, utiliser :

```text
www/public
```

Adapte seulement `<compte>` si Alwaysdata affiche ou demande le chemin absolu.

## Base de donnees Alwaysdata

Avant de configurer l'application, creer une base MySQL dans l'administration Alwaysdata :

- aller dans `Bases de donnees > MySQL` ;
- creer une base de donnees pour le projet ;
- creer ou relever l'utilisateur MySQL associe ;
- noter le nom de la base, l'utilisateur, le mot de passe et l'hote MySQL.

Alwaysdata utilise generalement un hote MySQL de ce type :

```text
mysql-<compte>.alwaysdata.net
```

Ces informations servent ensuite a construire la variable `DATABASE_URL`.

Créer ensuite un fichier `.env.local` a la racine du projet sur Alwaysdata pour renseigner la connexion MySQL de production :

```dotenv
APP_ENV=prod
APP_DEBUG=0
DATABASE_URL="mysql://<user>:<password>@mysql-<compte>.alwaysdata.net/<database>?serverVersion=8.0&charset=utf8mb4"
```

Le fichier `.env.local` ne doit pas etre versionne dans Git.

## Dump de la base de donnees

Depuis une connexion SSH Alwaysdata, il est possible de faire une sauvegarde SQL avec `mysqldump`.

Creer d'abord un dossier pour les sauvegardes :

```bash
mkdir -p ~/backups
```

Commande de dump :

```bash
mysqldump -h mysql-<compte>.alwaysdata.net -u <user> -p <database> > ~/backups/mediatekformation.sql
```

Le mot de passe MySQL est demande apres l'execution de la commande.

Pour generer un fichier date :

```bash
mysqldump -h mysql-<compte>.alwaysdata.net -u <user> -p <database> > ~/backups/mediatekformation-$(date +%Y-%m-%d_%H-%M-%S).sql
```

Pour restaurer un dump :

```bash
mysql -h mysql-<compte>.alwaysdata.net -u <user> -p <database> < ~/backups/mediatekformation.sql
```

## Acces SSH pour deployer depuis GitHub

Pour deployer depuis GitHub, il faut disposer d'un acces SSH Alwaysdata :

- aller dans `Acces distant > SSH` ;
- creer ou activer un identifiant SSH ;
- definir un mot de passe pour cet identifiant ;
- verifier que la connexion fonctionne depuis un terminal ;
- utiliser cet identifiant dans les secrets GitHub Actions si le deploiement est automatise.

Exemple de connexion :

```bash
ssh <identifiant>@ssh-<compte>.alwaysdata.net
```

Secrets GitHub Actions typiques :

```text
SSH_HOST=ssh-<compte>.alwaysdata.net
SSH_USER=<identifiant>
SSH_PASSWORD=<mot-de-passe>
```

## Variables d'environnement

En production, ne pas laisser les valeurs de developpement du fichier `.env`. Configurer plutot ces variables dans Alwaysdata ou dans un fichier `.env.local` non versionne :

```dotenv
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=<secret-long-et-unique>
DATABASE_URL="mysql://<user>:<password>@mysql-<compte>.alwaysdata.net/<database>?serverVersion=8.0&charset=utf8mb4"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
```

## Directives PHP

Dans `Environnement > PHP`, ou au niveau du site dans `Web > Sites`, ajouter ces directives si necessaire :

```ini
Voir le fichier docs/alwaysdata-php-prod.ini.
```

Extensions PHP a verifier pour ce projet :

```text
ctype
iconv
intl
mbstring
pdo_mysql
openssl
curl
zip
```

## Apache 2.4

Alwaysdata utilise Apache derriere PHP FastCGI et accepte les fichiers `.htaccess`. Le fichier `public/.htaccess` du projet redirige toutes les URL Symfony vers `public/index.php` et bloque l'acces aux fichiers caches.

Il n'est normalement pas necessaire d'ajouter un `VirtualHost` Apache manuel sur Alwaysdata. Si tu dois ajouter des directives globales, elles se configurent dans `Web > Configuration > Apache`.

## Commandes de deploiement

Depuis SSH, dans le dossier du projet :

```bash
composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
APP_ENV=prod APP_DEBUG=0 php bin/console cache:warmup
APP_ENV=prod APP_DEBUG=0 php bin/console doctrine:migrations:migrate
```

Si tu importes la base fournie avec le projet, utilise le fichier `mediatekformation.sql` avant de lancer l'application.
