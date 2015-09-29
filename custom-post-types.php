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
        'edit_item'          => __( 'Edit Student', 'asapkids' ),
        'view_item'          => __( 'View Student', 'asapkids' ),
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
            'supports' => array('')
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
        'edit_item'          => __( 'Edit Interest', 'asapkids' ),
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

    $cpt_organization_labels = array(
        'name'               => _x( 'Organizations', 'post type general name', 'asapkids' ),
        'singular_name'      => _x( 'Organization', 'post type singular name', 'asapkids' ),
        'menu_name'          => _x( 'Organizations', 'admin menu', 'asapkids' ),
        'name_admin_bar'     => _x( 'Organization', 'add new on admin bar', 'asapkids' ),
        'add_new'            => _x( 'Add New Organization', 'Organization', 'asapkids' ),
        'add_new_item'       => __( 'Add New Organization', 'asapkids' ),
        'new_item'           => __( 'New Organization', 'asapkids' ),
        'edit_item'          => __( 'Edit Organization', 'asapkids' ),
        'view_item'          => __( 'View Organization', 'asapkids' ),
        'all_items'          => __( 'All Organizations', 'asapkids' ),
        'search_items'       => __( 'Search Organizations', 'asapkids' ),
        'parent_item_colon'  => __( 'Parent Organizations:', 'asapkids' ),
        'not_found'          => __( 'No Organizations found.', 'asapkids' ),
        'not_found_in_trash' => __( 'No Organizations found in Trash.', 'asapkids' )
    );
    register_post_type(
        'cpt_organization',
        array(
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-networking',
            'menu_position' => 5,
            'labels' => $cpt_organization_labels,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'organizations' ),
        )
    );

    $cpt_programs_labels = array(
        'name'               => _x( 'Programs', 'post type general name', 'asapkids' ),
        'singular_name'      => _x( 'Program', 'post type singular name', 'asapkids' ),
        'menu_name'          => _x( 'Programs', 'admin menu', 'asapkids' ),
        'name_admin_bar'     => _x( 'Program', 'add new on admin bar', 'asapkids' ),
        'add_new'            => _x( 'Add New Program', 'Program', 'asapkids' ),
        'add_new_item'       => __( 'Add New Program', 'asapkids' ),
        'new_item'           => __( 'New Program', 'asapkids' ),
        'edit_item'          => __( 'Edit Program', 'asapkids' ),
        'view_item'          => __( 'View Program', 'asapkids' ),
        'all_items'          => __( 'All Programs', 'asapkids' ),
        'search_items'       => __( 'Search Programs', 'asapkids' ),
        'parent_item_colon'  => __( 'Parent Programs:', 'asapkids' ),
        'not_found'          => __( 'No Programs found.', 'asapkids' ),
        'not_found_in_trash' => __( 'No Programs found in Trash.', 'asapkids' )
    );
    register_post_type(
        'cpt_program',
        array(
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-art',
            'menu_position' => 6,
            'labels' => $cpt_programs_labels,
            'rewrite' => array( 'slug' => 'programs' ),
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