<?php

class Ern_Database_Service
{
    public function __construct()
    {
    }

    public static function create_db()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Création de la table joueur
        $table_joueur = $wpdb->prefix . 'joueur';
        $sql_joueur = "CREATE TABLE IF NOT EXISTS $table_joueur (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            prenom VARCHAR(255) NOT NULL,
            pseudo VARCHAR(255) NOT NULL
        ) $charset_collate;";
        $wpdb->query($sql_joueur);

        // Création de la table groupe
        $table_groupe = $wpdb->prefix . 'groupe';
        $sql_groupe = "CREATE TABLE IF NOT EXISTS $table_groupe (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(255) NOT NULL
        ) $charset_collate;";
        $wpdb->query($sql_groupe);

        // Création de la table competition
        $table_competition = $wpdb->prefix . 'competition';
        $sql_competition = "CREATE TABLE IF NOT EXISTS $table_competition (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(255) NOT NULL,
            nombre_joueur INT,
            date_debut DATE
        ) $charset_collate;";
        $wpdb->query($sql_competition);

        // Création de la table poule
        $table_poule = $wpdb->prefix . 'poule';
        $sql_poule = "CREATE TABLE IF NOT EXISTS $table_poule (
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur_id INT,
            competition_id INT,
            groupe_id INT,
            FOREIGN KEY (joueur_id) REFERENCES $table_joueur(id),
            FOREIGN KEY (competition_id) REFERENCES $table_competition(id),
            FOREIGN KEY (groupe_id) REFERENCES $table_groupe(id)
        ) $charset_collate;";
        $wpdb->query($sql_poule);

        // Création de la table points
        $table_points = $wpdb->prefix . 'points';
        $sql_points = "CREATE TABLE IF NOT EXISTS $table_points (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(255),
            points INT
        ) $charset_collate;";
        $wpdb->query($sql_points);

        // Création de la table match
        $table_match = $wpdb->prefix . 'match';
        $sql_match = "CREATE TABLE IF NOT EXISTS $table_match (
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur1_id INT,
            joueur2_id INT,
            date_match DATETIME,
            is_pool BOOLEAN,
            FOREIGN KEY (joueur1_id) REFERENCES $table_joueur(id),
            FOREIGN KEY (joueur2_id) REFERENCES $table_joueur(id)
        ) $charset_collate;";
        $wpdb->query($sql_match);

        // Création de la table score
        $table_score = $wpdb->prefix . 'score';
        $sql_score = "CREATE TABLE IF NOT EXISTS $table_score (
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur_id INT,
            match_id INT,
            point_id INT,
            FOREIGN KEY (joueur_id) REFERENCES $table_joueur(id),
            FOREIGN KEY (match_id) REFERENCES $table_match(id),
            FOREIGN KEY (point_id) REFERENCES $table_points(id)
        ) $charset_collate;";
        $wpdb->query($sql_score);


        return 'Tables créées avec succès.';
    }
}

