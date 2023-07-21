# Projet SAÉ 303

Code pour le projet de la SAÉ 303 : Concevoir des visualisations de données pour le web et un support animé

## Contenu

Ce dossier contient les fichiers nécessaires pour faire marcher une version préliminaire de l'application web. Le fichier `sae303.sql` contient la base de données.

## Installation

Instructions pour un déploiement local de développement sur votre ordinateur ou pour un déploiement de production sur le serveur web pédagogique.

### Développement

Vous avez besoin de XAMPP pour un déploiement. Étapes :

1. Créez une base de données appelée `sae303` avec phpMyAdmin et importez le fichier `sae303.sql`.
2. Copiez ce répertoire dans votre dossier `htdocs`.
3. Le site web est accessible localement à l'adresse URL `http://localhost/sae303/index.php`.

Vous trouverez plus d'explications [ici](https://aldo-gonzalez-lorenzo.pedaweb.univ-amu.fr/2021/r213-tp1.html).

### Production

Étapes :

1. Allez sur [phpMyAdmin](https://a-pedagoarles-mmi.aix.univ-amu.fr/phpmyadmin/), connectez-vous et importez le fichier `sae303.sql` sur votre base de données.
2. Copiez ce répertoire dans votre dossier avec FileZilla.
3. Modifiez vos coordonnées dans le fichier `include/php/functions.php`, lignes 16 et 17.
4. Modifiez la ligne 28 du fichier `include/php/functions.php` pour faire la connexion vers la base de données du serveur web.

Vous trouverez plus d'explications [ici](https://aldo-gonzalez-lorenzo.pedaweb.univ-amu.fr/2021/r213-tp2.html).
