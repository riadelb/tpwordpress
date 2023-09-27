<?php
// enregistrer les menus de navigation
function register_menu()
{
    // Enregistrement de menu-sup et menu-footer
    register_nav_menus(array(
        'menu-sup' => __('Menu supérieur'),     // menu sup
        'menu-footer' => __('Menu du pied de page') // menu footer
    ));
}
// init pour appeler la fonction register_menu
add_action('init', 'register_menu');

//  enregistrer des widgets personnalisés
function register_custom_widgets() {
    register_sidebar(array(
        'name' => __('Mon Widget Personnalisé'), // nom du widget
        'id' => 'mon_widget_personnalise',      // id du widget
        'description' => __('Un widget personnalisé pour le menu'), // description du widget
        'before_widget' => '<div id="%1$s" class="widget %2$s">', // balise de début du widget
        'after_widget' => '</div>', // balise de fin du widget
        'before_title' => '<h2 class="widget-title">', // balise de début du titre du widget
        'after_title' => '</h2>', // balise de fin du titre du widget
    ));
}
// widgets_init pour appeler la fonction register_custom_widgets
add_action('widgets_init', 'register_custom_widgets');


class Simple_menu extends Walker_Nav_Menu
{
    // méthode appelée pour afficher le début du menu
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        $title = $data_object->title;
        $permalink = $data_object->url;
        $output .= "<div class='nav-item'>";
        $output .= "<a class='nav-link bg-warning text-dark border m-1 custom_a' href='$permalink'>";
        $output .= $title;
        $output .= "</a>";
    }

    // méthode pour afficher la fin du menu
    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</div>";
    }
}


function add_joueur_menu() {
    add_menu_page(
        'Gestion des Joueurs',
        'Gestion des Joueurs',
        'manage_options',
        'joueur-menu',
        'display_joueur_page',
        'dashicons-id'
    );

    add_submenu_page(
        'joueur-menu',
        'Tous les Joueurs',
        'Tous les Joueurs',
        'manage_options',
        'joueurs-list',
        'display_joueurs_list'
    );
}
add_action('admin_menu', 'add_joueur_menu');


function display_joueur_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter des joueurs</h1>';

    // Vérifie si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // recupere et nettoie les données du formulaire
        $nom = sanitize_text_field($_POST['nom']);
        $prenom = sanitize_text_field($_POST['prenom']);
        $pseudo = sanitize_text_field($_POST['pseudo']);

        // pour accéder à la bdd
        global $wpdb;
        $table_joueur = $wpdb->prefix . 'joueur';

        // insere les données du joueur dans la bdd
        $wpdb->insert(
            $table_joueur,
            array(
                'nom' => $nom,
                'prenom' => $prenom,
                'pseudo' => $pseudo
            )
        );

        // affiche un message de réussite
        echo '<div class="updated"><p>Joueur ajouté avec succès.</p></div>';
    }

    // affiche le formulaire pour ajouter un joueur
    echo '<form method="post">';
    echo '<label for="nom">Nom :</label>';
    echo '<input type="text" name="nom"><br>';
    echo '<label for="prenom">Prénom :</label>';
    echo '<input type="text" name="prenom"><br>';
    echo '<label for="pseudo">Pseudo :</label>';
    echo '<input type="text" name="pseudo"><br>';
    echo '<input type="submit" value="Ajouter Joueur">';
    echo '</form>';

    echo '</div>';
}

function display_joueurs_list() {
    echo '<div class="wrap">';
    echo '<h1>Tous les Joueurs</h1>';

    // $wpdb pour accéder à la bdd
    global $wpdb;
    $table_joueur = $wpdb->prefix . 'joueur';

    // si formulaire de suppression est soumis
    if (isset($_POST['delete_joueur'])) {
        $joueur_id = intval($_POST['joueur_id']);
        // Supprime le joueur en utilisant son ID
        $wpdb->delete($table_joueur, array('id' => $joueur_id));
        echo '<div class="updated"><p>Joueur supprimé avec succès.</p></div>';
    }

    // recup la liste de tous les joueurs
    $joueurs = $wpdb->get_results("SELECT * FROM $table_joueur");

    // affiche la liste des joueurs dans un tableau
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Pseudo</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($joueurs as $joueur) {
        echo '<tr>';
        echo '<td>' . $joueur->id . '</td>';
        echo '<td>' . $joueur->nom . '</td>';
        echo '<td>' . $joueur->prenom . '</td>';
        echo '<td>' . $joueur->pseudo . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="joueur_id" value="' . $joueur->id . '">';
        echo '<button type="submit" name="delete_joueur" class="button button-secondary">Supprimer</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

function add_groupes_menu() {
    // Ajout de menu principal
    add_menu_page(
        'Gestion des Groupes',        // Titre de la page
        'Gestion des Groupes',        // Titre du menu
        'manage_options',             // Capabilité requise pour afficher ce menu
        'groupes-menu',               // Slug de la page
        'display_groupes_page',       // Callback pour afficher la page
        'dashicons-groups'            // Icône du menu (dashicons)
    );

    // Ajoute un sous-menu
    add_submenu_page(
        'groupes-menu',               // Slug du menu parent
        'Tous les Groupes',           // Titre de la page
        'Tous les Groupes',           // Titre du sous-menu
        'manage_options',             // Capabilité requise pour afficher cette page
        'groupes-list',               // Slug de la page
        'display_groupes_list'        // Callback pour afficher la page
    );
}
add_action('admin_menu', 'add_groupes_menu');

function display_groupes_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter un groupe</h1>';

    // verifie si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // recupere et nettoie le label du groupe
        $label = sanitize_text_field($_POST['label']);

        // pour accéder à la bdd
        global $wpdb;
        $table_groupe = $wpdb->prefix . 'groupe';

        // insere le groupe dans la table de la bdd
        $wpdb->insert(
            $table_groupe,
            array('label' => $label)
        );

        // affiche un message de réussite
        echo '<div class="updated"><p>Groupe ajouté avec succès.</p></div>';
    }

    // affiche le formulaire pour ajouter un groupe
    echo '<form method="post">';
    echo '<label for="label">Label :</label>';
    echo '<input type="text" name="label"><br>';
    echo '<input type="submit" value="Ajouter Groupe">';
    echo '</form>';

    echo '</div>';
}

// fonction pour afficher la liste des groupes
function display_groupes_list() {
    echo '<div class="wrap">';
    echo '<h1>Tous les Groupes</h1>';

    global $wpdb;
    $table_groupe = $wpdb->prefix . 'groupe';

    // verifie si le formulaire de suppression a été soumis
    if (isset($_POST['delete_groupe'])) {
        $groupe_id = intval($_POST['groupe_id']);

        // supprime le groupe sélectionné de la bdd
        $wpdb->delete($table_groupe, array('id' => $groupe_id));
        echo '<div class="updated"><p>Groupe supprimé avec succès.</p></div>';
    }

    // recupere tous les groupes depuis la base de données
    $groupes = $wpdb->get_results("SELECT * FROM $table_groupe");

    // affiche les groupes dans un tableau
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Label</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($groupes as $groupe) {
        echo '<tr>';
        echo '<td>' . $groupe->id . '</td>';
        echo '<td>' . $groupe->label . '</td>';
        echo '<td>';

        // formulaire pour supprimer un groupe
        echo '<form method="post">';
        echo '<input type="hidden" name="groupe_id" value="' . $groupe->id . '">';
        echo '<button type="submit" name="delete_groupe" class="button button-secondary">Supprimer</button>';
        echo '</form>';

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

// fonction pour ajouter le menu de gestion des compétitions
function add_competitions_menu() {
    add_menu_page(
        'Gestion des Compétitions',
        'Gestion des Compétitions',
        'manage_options',
        'competitions-menu',
        'display_competitions_page',
        'dashicons-awards'
    );

    // Ajoute une sous-page
    add_submenu_page(
        'competitions-menu',
        'Toutes les Compétitions',
        'Toutes les Compétitions',
        'manage_options',
        'competitions-list',
        'display_competitions_list'
    );
}

// ajoute le menu de gestion des compétitions à l'interface d'administration
add_action('admin_menu', 'add_competitions_menu');

// fonction pour afficher la page d'ajout de compétition
function display_competitions_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter une compétition</h1>';

    // verifie si le formulaire d'ajout de compétition a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $label = sanitize_text_field($_POST['label']);
        $nombre_joueur = intval($_POST['nombre_joueur']);
        $date_debut = date('Y-m-d', strtotime($_POST['date_debut']));

        global $wpdb;
        $table_competition = $wpdb->prefix . 'competition';

        // insere une nouvelle compétition dans la bdd
        $wpdb->insert(
            $table_competition,
            array(
                'label' => $label,
                'nombre_joueur' => $nombre_joueur,
                'date_debut' => $date_debut
            )
        );

        echo '<div class="updated"><p>Compétition ajoutée avec succès.</p></div>';
    }

    // formulaire d'ajout de compétition
    echo '<form method="post">';
    echo '<label for="label">Label :</label>';
    echo '<input type="text" name="label"><br>';
    echo '<label for="nombre_joueur">Nombre de joueurs :</label>';
    echo '<input type="number" name="nombre_joueur"><br>';
    echo '<label for="date_debut">Date de début :</label>';
    echo '<input type="text" name="date_debut"><br>';
    echo '<input type="submit" value="Ajouter Compétition">';
    echo '</form>';

    echo '</div>';
}

// fonction pour afficher la liste de toutes les compétitions
function display_competitions_list() {
    echo '<div class="wrap">';
    echo '<h1>Toutes les Compétitions</h1>';

    global $wpdb;
    $table_competition = $wpdb->prefix . 'competition';

    if (isset($_POST['delete_competition'])) {
        $competition_id = intval($_POST['competition_id']);

        // Supprime la compétition sélectionnée de la base de données
        $wpdb->delete($table_competition, array('id' => $competition_id));
        echo '<div class="updated"><p>Compétition supprimée avec succès.</p></div>';
    }

    // recupere toutes les compétitions depuis la bdd
    $competitions = $wpdb->get_results("SELECT * FROM $table_competition");

    // affiche les compétitions dans un tableau
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Label</th><th>Nombre de joueurs</th><th>Date de début</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($competitions as $competition) {
        echo '<tr>';
        echo '<td>' . $competition->id . '</td>';
        echo '<td>' . $competition->label . '</td>';
        echo '<td>' . $competition->nombre_joueur . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($competition->date_debut)) . '</td>';
        echo '<td>';

        // formulaire pour supprimer une compétition
        echo '<form method="post">';
        echo '<input type="hidden" name="competition_id" value="' . $competition->id . '">';
        echo '<button type="submit" name="delete_competition" class="button button-secondary">Supprimer</button>';
        echo '</form>';

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

// fonction pour ajouter un menu de gestion des poules dans le bo
function add_poules_menu() {
    // ajoute un menu principal
    add_menu_page(
        'Gestion des Poules',
        'Gestion des Poules',
        'manage_options',
        'poules-menu',
        'display_poules_page',
        'dashicons-networking'
    );

    // ajoute un sous-menu
    add_submenu_page(
        'poules-menu',
        'Toutes les Poules',
        'Toutes les Poules',
        'manage_options',
        'poules-list',
        'display_poules_list'
    );
}

add_action('admin_menu', 'add_poules_menu');

// fonction pour afficher la page d'ajout de Poule
function display_poules_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter une poule</h1>';

    global $wpdb;
    $table_joueurs = $wpdb->prefix . 'joueur';
    $table_competition = $wpdb->prefix . 'competition';
    $table_groupe = $wpdb->prefix . 'groupe';

    // verifie si le formulaire d'ajout de poule a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo_joueur = sanitize_text_field($_POST['pseudo_joueur']);
        $nom_competition = sanitize_text_field($_POST['nom_competition']);
        $nom_groupe = sanitize_text_field($_POST['nom_groupe']);

        // recup l'id du joueur en fonction du pseudo
        $joueur_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_joueurs WHERE pseudo = %s", $pseudo_joueur));

        // recup l'id de la compétition en fonction du nom
        $competition_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_competition WHERE label = %s", $nom_competition));

        // recup l'id du groupe en fonction du nom
        $groupe_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_groupe WHERE label = %s", $nom_groupe));

        // insertion des données de la poule dans la bdd
        $table_poule = $wpdb->prefix . 'poule';
        $wpdb->insert(
            $table_poule,
            array(
                'joueur_id' => $joueur_id,
                'competition_id' => $competition_id,
                'groupe_id' => $groupe_id
            )
        );

        echo '<div class="updated"><p>Poule ajoutée avec succès.</p></div>';
    }

    // recup la liste des pseudonymes des joueurs
    $joueurs = $wpdb->get_col("SELECT pseudo FROM $table_joueurs");

    // recup la liste des noms de compétitions
    $competitions = $wpdb->get_col("SELECT label FROM $table_competition");

    // recupere la liste des noms de groupes
    $groupes = $wpdb->get_col("SELECT label FROM $table_groupe");

    // formulaire d'ajout de poule
    echo '<form method="post">';
    echo '<label for="pseudo_joueur">Pseudo du Joueur :</label>';
    echo '<select name="pseudo_joueur">';
    foreach ($joueurs as $joueur) {
        echo '<option value="' . esc_html($joueur) . '">' . esc_html($joueur) . '</option>';
    }
    echo '</select><br>';
    echo '<label for="nom_competition">Nom de la Compétition :</label>';
    echo '<select name="nom_competition">';
    foreach ($competitions as $competition) {
        echo '<option value="' . esc_html($competition) . '">' . esc_html($competition) . '</option>';
    }
    echo '</select><br>';
    echo '<label for="nom_groupe">Nom du Groupe :</label>';
    echo '<select name="nom_groupe">';
    foreach ($groupes as $groupe) {
        echo '<option value="' . esc_html($groupe) . '">' . esc_html($groupe) . '</option>';
    }
    echo '</select><br>';
    echo '<input type="submit" value="Ajouter Poule">';
    echo '</form>';

    echo '</div>';
}

// fonction pour afficher la liste de toutes les poules
function display_poules_list() {
    echo '<div class="wrap">';
    echo '<h1>Toutes les Poules</h1>';

    global $wpdb;
    $table_poule = $wpdb->prefix . 'poule';
    $table_joueur = $wpdb->prefix . 'joueur';
    $table_competition = $wpdb->prefix . 'competition';
    $table_groupe = $wpdb->prefix . 'groupe';

    // verifie si le formulaire de suppression de poule a été soumis
    if (isset($_POST['delete_poule'])) {
        $poule_id = intval($_POST['poule_id']);
        $wpdb->delete($table_poule, array('id' => $poule_id));
        echo '<div class="updated"><p>Poule supprimée avec succès.</p></div>';
    }

    // recupere toutes les poules avec des informations sur les joueurs, les compet et les groupes
    $poules = $wpdb->get_results("SELECT p.id, j.pseudo AS joueur_pseudo, c.label AS competition_label, g.label AS groupe_label 
                                  FROM $table_poule p 
                                  JOIN $table_joueur j ON p.joueur_id = j.id
                                  JOIN $table_competition c ON p.competition_id = c.id
                                  JOIN $table_groupe g ON p.groupe_id = g.id");

    // affiche un tableau avec les données des poules
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Joueur</th><th>Compétition</th><th>Groupe</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($poules as $poule) {
        echo '<tr>';
        echo '<td>' . $poule->id . '</td>';
        echo '<td>' . $poule->joueur_pseudo . '</td>';
        echo '<td>' . $poule->competition_label . '</td>';
        echo '<td>' . $poule->groupe_label . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="poule_id" value="' . $poule->id . '">';
        echo '<button type="submit" name="delete_poule" class="button button-secondary">Supprimer</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

function add_points_menu() {
    // ajout menu principal
    add_menu_page(
        'Gestion des Points',
        'Gestion des Points',
        'manage_options',
        'points-menu',
        'display_points_page',
        'dashicons-chart-bar'
    );

    // Ajoute un sous-menu
    add_submenu_page(
        'points-menu',
        'Tous les Points',
        'Tous les Points',
        'manage_options',
        'points-list',
        'display_points_list'
    );
}

// ajouter le menu des points
add_action('admin_menu', 'add_points_menu');

// fonction pour afficher la page d'ajout d'un point
function display_points_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter un point</h1>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $label = sanitize_text_field($_POST['label']);

        // inserer le point dans la bdd
        global $wpdb;
        $table_points = $wpdb->prefix . 'points';
        $wpdb->insert(
            $table_points,
            array('label' => $label)
        );

        echo '<div class="updated"><p>Point ajouté avec succès.</p></div>';
    }

    // formulaire pour ajouter un point
    echo '<form method="post">';
    echo '<label for="label">Label :</label>';
    echo '<input type="text" name="label"><br>';
    echo '<input type="submit" value="Ajouter Point">';
    echo '</form>';

    echo '</div>';
}

// fonction pour afficher la liste de tous les points
function display_points_list() {
    echo '<div class="wrap">';
    echo '<h1>Tous les Points</h1>';

    global $wpdb;
    $table_points = $wpdb->prefix . 'points';

    // verifie si le formulaire de suppression de point a été soumis
    if (isset($_POST['delete_point'])) {
        $point_id = intval($_POST['point_id']);
        $wpdb->delete($table_points, array('id' => $point_id));
        echo '<div class="updated"><p>Point supprimé avec succès.</p></div>';
    }

    // Récupère tous les points depuis la base de données
    $points = $wpdb->get_results("SELECT * FROM $table_points");

    // affiche un tableau avec les donnes des points
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Label</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($points as $point) {
        echo '<tr>';
        echo '<td>' . $point->id . '</td>';
        echo '<td>' . $point->label . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="point_id" value="' . $point->id . '">';
        echo '<button type="submit" name="delete_point" class="button button-secondary">Supprimer</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

// fonction pour ajouter le menu scores
function add_scores_menu() {
    add_menu_page(
        'Gestion des Scores',
        'Gestion des Scores',
        'manage_options',
        'scores-menu',
        'display_scores_page',
        'dashicons-star-filled'
    );

    add_submenu_page(
        'scores-menu',
        'Tous les Scores',
        'Tous les Scores',
        'manage_options',
        'scores-list',
        'display_scores_list'
    );
}
add_action('admin_menu', 'add_scores_menu');

// fonction pour afficher la page d'ajout d'un score
function display_scores_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter un score</h1>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $joueur_id = intval($_POST['joueur_id']);
        $match_id = intval($_POST['match_id']);
        $point_id = intval($_POST['point_id']);

        // inserer le score dans la base de données
        global $wpdb;
        $table_score = $wpdb->prefix . 'score';
        $wpdb->insert(
            $table_score,
            array(
                'joueur_id' => $joueur_id,
                'match_id' => $match_id,
                'point_id' => $point_id
            )
        );

        echo '<div class="updated"><p>Score ajouté avec succès.</p></div>';
    }

    // formulaire pour ajouter un score
    echo '<form method="post">';
    echo '<label for="joueur_id">ID du Joueur :</label>';
    echo '<input type="number" name="joueur_id"><br>';
    echo '<label for="match_id">ID du Match :</label>';
    echo '<input type="number" name="match_id"><br>';
    echo '<label for="point_id">ID du Point :</label>';
    echo '<input type="number" name="point_id"><br>';
    echo '<input type="submit" value="Ajouter Score">';
    echo '</form>';

    echo '</div>';
}

// fonction pour afficher la liste des scores
function display_scores_list() {
    echo '<div class="wrap">';
    echo '<h1>Tous les Scores</h1>';

    global $wpdb;
    $table_score = $wpdb->prefix . 'score';

    if (isset($_POST['delete_score'])) {
        $score_id = intval($_POST['score_id']);
        $wpdb->delete($table_score, array('id' => $score_id));
        echo '<div class="updated"><p>Score supprimé avec succès.</p></div>';
    }

    $scores = $wpdb->get_results("SELECT * FROM $table_score");

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Joueur ID</th><th>Match ID</th><th>Point ID</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    foreach ($scores as $score) {
        echo '<tr>';
        echo '<td>' . $score->id . '</td>';
        echo '<td>' . $score->joueur_id . '</td>';
        echo '<td>' . $score->match_id . '</td>';
        echo '<td>' . $score->point_id . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="score_id" value="' . $score->id . '">';
        echo '<button type="submit" name="delete_score" class="button button-secondary">Supprimer</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

// fonction pour ajouter le menu matches
function add_matches_menu() {
    add_menu_page(
        'Gestion des Matches',
        'Gestion des Matches',
        'manage_options',
        'matches-menu',
        'display_matches_page', // page des matches
        'dashicons-clipboard'
    );

    add_submenu_page(
        'matches-menu',
        'Tous les Matches',
        'Tous les Matches',
        'manage_options',
        'matches-list',
        'display_matches_list' // page de la liste des matches
    );
}
add_action('admin_menu', 'add_matches_menu');

function display_matches_page() {
    echo '<div class="wrap">';
    echo '<h1>Ajouter un match</h1>';

    // recuperer la liste des joueurs pour la liste
    global $wpdb;
    $table_joueur = $wpdb->prefix . 'joueur';
    $players = $wpdb->get_results("SELECT id, pseudo FROM $table_joueur");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $joueur1_id = intval($_POST['joueur1_id']);
        $joueur2_id = intval($_POST['joueur2_id']);
        $date_match = sanitize_text_field($_POST['date_match']);
        $is_pool = intval($_POST['is_pool']);

        // inserer le match dans la base de données
        $table_match = $wpdb->prefix . 'match';
        $wpdb->insert(
            $table_match,
            array(
                'joueur1_id' => $joueur1_id,
                'joueur2_id' => $joueur2_id,
                'date_match' => $date_match,
                'is_pool' => $is_pool
            )
        );

        echo '<div class="updated"><p>Match ajouté avec succès.</p></div>';
    }

    echo '<form method="post">';
    echo '<label for="joueur1_id">Joueur 1 :</label>';
    echo '<select name="joueur1_id">';
    foreach ($players as $player) {
        echo '<option value="' . $player->id . '">' . $player->pseudo . '</option>';
    }
    echo '</select><br>';

    echo '<label for="joueur2_id">Joueur 2 :</label>';
    echo '<select name="joueur2_id">';
    foreach ($players as $player) {
        echo '<option value="' . $player->id . '">' . $player->pseudo . '</option>';
    }
    echo '</select><br>';

    echo '<label for="date_match">Date du Match :</label>';
    echo '<input type="date" name="date_match"><br>';
    echo '<label for="is_pool">Est un match de poule (0 ou 1) :</label>';
    echo '<input type="number" name="is_pool"><br>';
    echo '<input type="submit" value="Ajouter Match">';
    echo '</form>';

    echo '</div>';
}

function display_matches_list() {
    echo '<div class="wrap">';
    echo '<h1>Tous les Matches</h1>';

    global $wpdb;
    $table_match = $wpdb->prefix . 'match';
    $table_joueur = $wpdb->prefix . 'joueur';

    if (isset($_POST['delete_match']) && current_user_can('manage_options')) {
        $match_id = intval($_POST['match_id']);
        $wpdb->delete($table_match, array('id' => $match_id));
        echo '<div class="updated"><p>Match supprimé avec succès.</p></div>';
    }

    $matches = $wpdb->get_results("SELECT * FROM $table_match");

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Joueur 1</th><th>Joueur 2</th><th>Date du Match</th><th>Est un match de poule</th>';

    if (current_user_can('manage_options')) { // verifier les droits de l'utilisateur
        echo '<th>Actions</th>';
    }

    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($matches as $match) {
        echo '<tr>';
        echo '<td>' . $match->id . '</td>';

        // recuperer les pseudos des joueurs à partir de leurs ids
        $joueur1_pseudo = $wpdb->get_var($wpdb->prepare("SELECT pseudo FROM $table_joueur WHERE id = %d", $match->joueur1_id));
        $joueur2_pseudo = $wpdb->get_var($wpdb->prepare("SELECT pseudo FROM $table_joueur WHERE id = %d", $match->joueur2_id));

        echo '<td>' . $joueur1_pseudo . '</td>';
        echo '<td>' . $joueur2_pseudo . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($match->date_match)) . '</td>';
        echo '<td>' . $match->is_pool . '</td>';

        if (current_user_can('manage_options')) {
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="match_id" value="' . $match->id . '">';
            echo '<button type="submit" name="delete_match" class="button button-secondary">Supprimer</button>';
            echo '</form>';
            echo '</td>';
        }

        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}
