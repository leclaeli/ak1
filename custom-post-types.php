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
    $cpt_student_labels = array(
        'name'               => _x( 'Students', 'post type general name', 'asapkids' ),
        'singular_name'      => _x( 'Student', 'post type singular name', 'asapkids' ),
        'menu_name'          => _x( 'Students', 'admin menu', 'asapkids' ),
        'name_admin_bar'     => _x( 'Student', 'add new on admin bar', 'asapkids' ),
        'add_new'            => _x( 'Add New Student', 'student', 'asapkids' ),
        'add_new_item'       => __( 'Add New Student', 'asapkids' ),
        'new_item'           => __( 'New Student', 'asapkids' ),
        'edit_item'          => __( 'Edit tudent', 'asapkids' ),
        'view_item'          => __( 'View student', 'asapkids' ),
        'all_items'          => __( 'All Students', 'asapkids' ),
        'search_items'       => __( 'Search Students', 'asapkids' ),
        'parent_item_colon'  => __( 'Parent Students:', 'asapkids' ),
        'not_found'          => __( 'No students found.', 'asapkids' ),
        'not_found_in_trash' => __( 'No students found in Trash.', 'asapkids' )
    );
    register_post_type(
        'cpt_student',
        array(
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-groups',
            'menu_position' => 9,
            'labels' => $cpt_student_labels,
            'supports' => array('author'),
        )
    );

    $cpt_interest_labels = array(
        'name'               => _x( 'Interests', 'post type general name', 'asapkids' ),
        'singular_name'      => _x( 'Interest', 'post type singular name', 'asapkids' ),
        'menu_name'          => _x( 'Interests', 'admin menu', 'asapkids' ),
        'name_admin_bar'     => _x( 'Interest', 'add new on admin bar', 'asapkids' ),
        'add_new'            => _x( 'Add New Interest', 'Interest', 'asapkids' ),
        'add_new_item'       => __( 'Add New Interest', 'asapkids' ),
        'new_item'           => __( 'New Interest', 'asapkids' ),
        'edit_item'          => __( 'Edit tudent', 'asapkids' ),
        'view_item'          => __( 'View Interest', 'asapkids' ),
        'all_items'          => __( 'All Interests', 'asapkids' ),
        'search_items'       => __( 'Search Interests', 'asapkids' ),
        'parent_item_colon'  => __( 'Parent Interests:', 'asapkids' ),
        'not_found'          => __( 'No interests found.', 'asapkids' ),
        'not_found_in_trash' => __( 'No interests found in Trash.', 'asapkids' )
    );
    register_post_type(
        'cpt_interest',
        array(
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-heart',
            'menu_position' => 8,
            'labels' => $cpt_interest_labels,
            'taxonomies' => array('interest_type'),
            'supports' => array('title')
        )
    );

    register_post_type(
        'cpt_organization',
        array(
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-networking',
            'menu_position' => 5,
            'label' => 'Organizations',
        	'has_archive' => true,
        	'rewrite' => array( 'slug' => 'organizations' ),
        )
    );

    register_post_type(
        'cpt_program',
        array(
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-art',
            'menu_position' => 6,
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