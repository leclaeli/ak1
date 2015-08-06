<?php 
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/

function create_my_post_types() {
    register_post_type(
        'cpt_student',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Students',
            'supports' => array('')
        )
    );

    register_post_type(
        'cpt_interest',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Interests',
            'taxonomies' => array('interest_type'),
            'supports' => array('title')
        )
    );

    register_post_type(
        'cpt_organization',
        array(
            'public' => true,
            'show_ui' => true,
            'label' => 'Organizations',
        	'has_archive' => true,
        )
    );

    register_post_type(
        'cpt_program',
        array(
            'public' => true,
            'show_ui' => true,
            'label' => 'Programs',
            'supports' => array('thumbnail', 'title', 'editor'),
        )
    );
}
add_action( 'init', 'create_my_post_types' );

function asapkids_interests_taxonomy() {
   register_taxonomy(
       'interest_type',
       'cpt_interest',
       array(
           'label' => __( 'Type' ),
           'rewrite' => array( 'slug' => 'type' ),
           'hierarchical' => true,
       )
   );
}
add_action( 'init', 'asapkids_interests_taxonomy' ); 