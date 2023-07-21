<?php

/**
 * Coordonnées de connexion à la base de données en local
 */
const db_local = [
    'dbname' => 'sae303', 
    'user' => 'root',
    'pwd' => ''     // 'root' pour MAMP
];

/**
 * Coordonnées de connexion à la base de données sur un serveur
 */
const db_serveur = [
    'dbname' => 'd21214065',  // par exemple 
    'user' => 'd21214065',
    'pwd' => 'secret'
];

/**
 * Connexion à la base de données
 * 
 * Mettre la variable $local à true si le site web est déployé en local (avec XAMPP),
 * ou false s'il est déployé sur un serveur web.
 */
function db_connection() {
    $local = false;
    if ($local) {
        $dbh = new PDO(
            'mysql:host=localhost;dbname=' . db_local['dbname'] . ';charset=utf8', 
            db_local['user'], 
            db_local['pwd']
        );
    } else {
        $dbh = new PDO(
            'mysql:host=localhost;dbname=' . db_serveur['dbname'] . ';charset=utf8', 
            db_serveur['user'], 
            db_serveur['pwd']
        );
    }
    return $dbh;
}
?>