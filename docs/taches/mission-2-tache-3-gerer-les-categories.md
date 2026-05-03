# Mission 2 - Tache 3 : gerer les categories

Commit de reference : `e1f1ab1` - `Mission 2 : Tache 3 : gerer les categories`

## Objectif

Ajouter une interface d'administration permettant de gerer les categories.

## Travaux realises

- Creation du controleur `AdminCategorieController`.
- Creation des routes d'administration sous `/admin/categories`.
- Ajout d'une page unique pour l'ajout et la liste des categories.
- Ajout du formulaire de creation d'une categorie.
- Ajout de la suppression d'une categorie en `POST`.
- Detachement des formations associees avant suppression de la categorie.
- Creation du type de formulaire `CategorieType`.
- Affichage du nombre de formations rattachees a chaque categorie.
- Ajout de l'entree `Categories` dans la navigation admin.

## Routes ajoutees

- `/admin/categories`
- `/admin/categories/{id}/supprimer`

## Fichiers ajoutes ou modifies

- `src/Controller/AdminCategorieController.php`
- `src/Form/CategorieType.php`
- `templates/admin/base.html.twig`
- `templates/admin/categories/index.html.twig`

## Resultat

Le back office permet maintenant d'ajouter, lister et supprimer les categories, avec affichage du nombre de formations associees.

