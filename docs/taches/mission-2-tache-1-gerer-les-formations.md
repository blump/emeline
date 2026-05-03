# Mission 2 - Tache 1 : gerer les formations

Commit de reference : `942add1` - `Mission 2 : Tache 1 : gerer les formations`

## Objectif

Ajouter une interface d'administration permettant de gerer les formations.

## Travaux realises

- Creation du controleur `AdminFormationController`.
- Creation des routes d'administration sous `/admin/formations`.
- Ajout de la liste des formations triees par date de publication decroissante.
- Ajout du formulaire de creation d'une formation.
- Ajout du formulaire de modification d'une formation.
- Ajout de la suppression d'une formation en `POST`.
- Protection de la suppression avec un jeton CSRF.
- Creation du type de formulaire `FormationType`.
- Ajout des champs de formulaire : titre, date de publication, identifiant YouTube, description, playlist et categories.
- Creation des templates Twig d'administration des formations.
- Creation d'un layout admin commun.
- Ajout d'un lien vers le back office dans le layout front.

## Routes ajoutees

- `/admin/formations`
- `/admin/formations/ajout`
- `/admin/formations/{id}/modifier`
- `/admin/formations/{id}/supprimer`

## Fichiers ajoutes ou modifies

- `src/Controller/AdminFormationController.php`
- `src/Form/FormationType.php`
- `templates/admin/base.html.twig`
- `templates/admin/formations/_form.html.twig`
- `templates/admin/formations/edit.html.twig`
- `templates/admin/formations/index.html.twig`
- `templates/admin/formations/new.html.twig`
- `templates/basefront.html.twig`

## Resultat

Le back office dispose d'un premier module complet de gestion des formations : consultation, creation, modification et suppression.

