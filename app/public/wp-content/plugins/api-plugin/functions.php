<?php

/* 
    Plugin Name:  API-PLUGIN
    Plugin URI:   https://www.tutowp.fr/tutowp
    Description:  Permet de faire des appels à l'API.
    Version:      1.0
    Author:       Nejma
    Author URI:   https://www.tutowp.fr/tutowp

 */


add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
    function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
/* ----------------------------------------------------------------------------------- */

/*
* On utilise une fonction pour créer notre custom post type 'Memes'
*/

function wpm_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Memes', 'Post Type General Name'),
		// Le nom au singulier
		'singular_name'       => _x( 'Memes', 'Post Type Singular Name'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Memes'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Tous les Memes'),
		'view_item'           => __( 'Voir les Memes'),
		'add_new_item'        => __( 'Ajouter un nouveau Memes'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer le Memes'),
		'update_item'         => __( 'Modifier le Memes'),
		'search_items'        => __( 'Rechercher un Memes'),
		'not_found'           => __( 'Non trouvé'),
		'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Memes'),
		'description'         => __( 'Tous sur les Memes'),
		'labels'              => $labels,
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'thumbnail'),
		/* 
		* Différentes options supplémentaires
		*/
		'show_in_rest' => true,
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
		'rewrite'			  => array( 'slug' => 'Memes'),

	);
	
	// On enregistre notre custom post type qu'on nomme ici "memes" et ses arguments
	register_post_type( 'Memes', $args );

}
add_action( 'init', 'wpm_custom_post_type', 0 );

/* ------------------------------------------------------------------ */

/* 
    Génération de mon url de posts random
    http://starwars.local/wp-json/kadence-child/v1/random-memes/
*/

add_action('rest_api_init', function () {
    register_rest_route( 'kadence-child/v1', 'random-memes/',array(
        'methods'  => 'GET',
        'callback' => 'get_random_memes'
    ));
});

function get_random_memes() {

    /* Arguments attendus pour la requête */
    $args = array(
            'post_type' => 'Memes',
            'orderby'   => 'rand',
            'posts_per_page' => 1, 
    );

    /* La requête avec WP_QUERY avec les arguments du dessus */
    $the_query = new WP_Query( $args );
    
    /* On recupère les posts */
    $meme = $the_query->get_posts();
    
    /* Conditions s'il n'y a pas de meme */
    if (empty($meme)) {
        return new WP_Error( 'Meme non trouve', 'Il n\'y a pas de memes', array('status' => 404) );
    }

    /* On retourne un array avec la data */
    return [
        "title" => get_the_title($meme[0]->ID),
        "thumbnail" => get_the_post_thumbnail($meme[0]->ID)
    ]; 
}

/* ------------------------------------------------------------------ */

/* Visuel d'un meme  */
function show_random_meme(){
    
    $display = "<div id='showMeme'>";

    $display .= "</div>";

    return $display;
}

add_shortcode('wpb-random-posts','show_random_meme');
add_filter('widget_text', 'do_shortcode'); 

