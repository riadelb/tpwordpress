<?php
/*
Plugin Name: Custom Match Widget
Description: Widget
Version: 1.0
Author: Riad
*/

// Fonction pour afficher la liste des matches
function display_matches_list_widget() {
    echo '<div class="wrap">';

    global $wpdb;
    $table_match = $wpdb->prefix . 'match';
    $table_joueur = $wpdb->prefix . 'joueur';

    $matches = $wpdb->get_results("SELECT * FROM $table_match");

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Joueur 1</th><th>Joueur 2</th><th>Date du Match</th><th>Est un match de poule</th></tr></thead>';
    echo '<tbody>';

    foreach ($matches as $match) {
        echo '<tr>';
        echo '<td>' . $match->id . '</td>';

        $joueur1_pseudo = $wpdb->get_var($wpdb->prepare("SELECT pseudo FROM $table_joueur WHERE id = %d", $match->joueur1_id));
        $joueur2_pseudo = $wpdb->get_var($wpdb->prepare("SELECT pseudo FROM $table_joueur WHERE id = %d", $match->joueur2_id));

        echo '<td>' . $joueur1_pseudo . '</td>';
        echo '<td>' . $joueur2_pseudo . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($match->date_match)) . '</td>';
        echo '<td>' . ($match->is_pool ? 'Oui' : 'Non') . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

function custom_match_widget_shortcode() {
    ob_start();
    display_matches_list_widget();
    return ob_get_clean();
}
add_shortcode('custom_match_widget', 'custom_match_widget_shortcode');

class Custom_Match_Widget extends WP_Widget {

    // Constructeur du widget
    public function __construct() {
        parent::__construct(
            'custom_match_widget',
            'Custom Match Widget',
            array('description' => 'Widget personnalisé pour afficher la liste des matches.')
        );
    }

    // Méthode d'affichage du widget
    public function widget($args, $instance) {
        echo $args['before_widget'];
        display_matches_list();
        echo $args['after_widget'];
    }
}

// Fonction pour enregistrer le widget
function register_custom_match_widget() {
    register_widget('Custom_Match_Widget');
}

add_action('widgets_init', 'register_custom_match_widget');
