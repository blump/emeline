# Mission 2 - Tache 2 : gerer les playlists

Commit de reference : `f120c1e` - `Mission 2 : Tache 2 : gerer les playlists`

## Objectif

Ajouter une interface d'administration permettant de gerer les playlists.

## Travaux realises

- Creation du controleur `AdminPlaylistController`.
- Creation des routes d'administration sous `/admin/playlists`.
- Ajout de la liste des playlists triees par nom.
- Affichage du nombre de formations par playlist dans la liste admin.
- Affichage d'un extrait de description dans la liste admin.
- Ajout du formulaire de creation d'une playlist.
- Ajout du formulaire de modification d'une playlist.
- Ajout de la suppression d'une playlist en `POST`.
- Detachement des formations associees avant suppression de la playlist.
- Creation du type de formulaire `PlaylistType`.
- Creation des templates Twig d'administration des playlists.
- Ajout de l'entree `Playlists` dans la navigation admin.

## Routes ajoutees

- `/admin/playlists`
- `/admin/playlists/ajout`
- `/admin/playlists/{id}/modifier`
- `/admin/playlists/{id}/supprimer`

## Fichiers ajoutes ou modifies

- `src/Controller/AdminPlaylistController.php`
- `src/Form/PlaylistType.php`
- `templates/admin/base.html.twig`
- `templates/admin/playlists/_form.html.twig`
- `templates/admin/playlists/edit.html.twig`
- `templates/admin/playlists/index.html.twig`
- `templates/admin/playlists/new.html.twig`

## Resultat

Le back office permet maintenant de consulter, creer, modifier et supprimer les playlists, avec prise en compte des formations rattachees avant suppression.

