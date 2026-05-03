# Mission 1 - Tache 2 : ajouter une fonctionnalite

Commit de reference : `e47d669` - `Mission 1 : Tache 2 : ajouter une fonctionnalite`

## Objectif

Ajouter une fonctionnalite permettant d'afficher et de trier les playlists selon leur nombre de formations.

## Travaux realises

- Ajout du nombre de formations rattachees a une playlist.
- Ajout d'une methode metier `getNombreFormations()` dans l'entite `Playlist`.
- Ajout d'une requete repository pour trier les playlists par nombre de formations.
- Mise a jour du controleur des playlists pour accepter le tri sur le champ `formations`.
- Ajout d'une colonne `nombre de formations` dans la liste publique des playlists.
- Ajout des boutons de tri ascendant et descendant sur cette nouvelle colonne.
- Affichage du nombre de formations dans la page de detail d'une playlist.

## Fichiers modifies

- `src/Controller/PlaylistsController.php`
- `src/Entity/Playlist.php`
- `src/Repository/PlaylistRepository.php`
- `templates/pages/playlist.html.twig`
- `templates/pages/playlists.html.twig`

## Resultat

La page publique des playlists permet maintenant de visualiser rapidement le volume de formations par playlist et de trier les playlists selon ce nombre.

