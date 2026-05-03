# Tests de compatibilite navigateurs

Tests manuels a realiser sur Chrome et Firefox.

## Parcours a verifier

1. Ouvrir la page d accueil `/`.
   Resultat attendu : la page s affiche sans erreur, le menu est visible, les deux dernieres formations apparaissent.

2. Ouvrir `/formations`.
   Resultat attendu : la liste s affiche, les boutons de tri fonctionnent, le filtre par titre fonctionne, le filtre par categorie fonctionne.

3. Ouvrir `/playlists`.
   Resultat attendu : la liste s affiche, la colonne `nombre de formations` est visible, le tri sur cette colonne fonctionne.

4. Ouvrir `/login` puis se connecter avec `emeline / enileme51`.
   Resultat attendu : redirection vers le back office.

5. Tester le back office formations, playlists et categories.
   Resultat attendu : les formulaires restent utilisables, les boutons d ajout et de suppression sont cliquables, la mise en page reste correcte.

## Points d attention

- verifier que les tableaux Bootstrap gardent une mise en page correcte
- verifier les formulaires de connexion et du back office
- verifier qu aucune erreur JavaScript n apparait dans la console navigateur
