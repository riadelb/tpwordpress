<?php

namespace widget;
use WP_Query;
use WP_Widget;

class MatchWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'match_widget',
            'Widget des matchs',
            array('description' => 'Affiche les matchs')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Matchs' . $args['after_title'];

        // Requête pour récupérer les matchs
        $matches_query = new WP_Query(array(
            'post_type' => 'matches', // Remplacez 'matches' par le nom de votre type de publication personnalisé
            'posts_per_page' => -1, // Récupérer tous les matchs
        ));

        if ($matches_query->have_posts()) {
            echo '<ul>';
            while ($matches_query->have_posts()) {
                $matches_query->the_post();
                echo '<li>' . get_the_title() . '</li>'; // Afficher les données du match, personnalisez selon vos besoins
            }
            echo '</ul>';
        } else {
            echo 'Aucun match trouvé.';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }
}
