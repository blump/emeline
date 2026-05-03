# Mission 2 - Tache 4 : ajouter l'acces avec authentification

Commit de reference : `00e3b50` - `Mission 2 : Tache 4 : ajouter l'acces avec authentification`

## Objectif

Restreindre l'acces au back office avec une authentification administrateur.

## Travaux realises

- Configuration du provider utilisateur en memoire dans `security.yaml`.
- Ajout de l'utilisateur `emeline` avec le role `ROLE_ADMIN`.
- Activation de l'authentification par formulaire.
- Configuration de la route de connexion `/login`.
- Configuration de la route de deconnexion `/logout`.
- Redirection apres connexion vers la gestion des formations.
- Ajout d'une regle d'acces : toutes les routes `/admin` necessitent `ROLE_ADMIN`.
- Ajout du controleur `SecurityController`.
- Creation du template de connexion.
- Ajout d'un lien de deconnexion dans le layout admin.
- Adaptation du lien `Back office` cote front : il mene au back office si l'utilisateur est connecte, sinon a la page de connexion.

## Routes ajoutees

- `/login`
- `/logout`

## Fichiers ajoutes ou modifies

- `config/packages/security.yaml`
- `src/Controller/SecurityController.php`
- `templates/admin/base.html.twig`
- `templates/basefront.html.twig`
- `templates/security/login.html.twig`

## Resultat

Le back office n'est plus accessible publiquement. L'acces aux routes `/admin` est reserve a un utilisateur authentifie avec le role `ROLE_ADMIN`.

