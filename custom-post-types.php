<?php 
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
// function prefix_register_name() {

//     $labels_customers = array(
//         'name'                => __( 'Customers', 'text-domain' ),
//         'Customer_name'       => __( 'Customer', 'text-domain' ),
//         'add_new'             => _x( 'Add New Customer Name', 'text-domain', 'text-domain' ),
//         'add_new_item'        => __( 'Add New Customer Name', 'text-domain' ),
//         'edit_item'           => __( 'Edit Customer Name', 'text-domain' ),
//         'new_item'            => __( 'New Customer Name', 'text-domain' ),
//         'view_item'           => __( 'View Customer Name', 'text-domain' ),
//         'search_items'        => __( 'Search Customers Name', 'text-domain' ),
//         'not_found'           => __( 'No Customers Name found', 'text-domain' ),
//         'not_found_in_trash'  => __( 'No Customers Name found in Trash', 'text-domain' ),
//         'parent_item_colon'   => __( 'Parent Customer Name:', 'text-domain' ),
//         'menu_name'           => __( 'Customers', 'text-domain' ),
//     );

//     $args = array(
//         'labels'              => $labels_customers,
//         'hierarchical'        => false,
//         'description'         => 'description',
//         'taxonomies'          => array(),
//         'public'              => false,
//         'show_ui'             => true,
//         'show_in_menu'        => true,
//         'show_in_admin_bar'   => true,
//         'menu_position'       => null,
//         'menu_icon'           => null,
//         'show_in_nav_menus'   => true,
//         'publicly_queryable'  => false,
//         'exclude_from_search' => false,
//         'has_archive'         => true,
//         'query_var'           => false,
//         'can_export'          => true,
//         'rewrite'             => true,
//         'capability_type'     => 'customer',
//         'capabilities'        => array(
//             'publish_posts' => 'publish_customers',
//             'edit_posts' => 'edit_customers',
//             'edit_others_posts' => 'edit_others_customers',
//             'delete_posts' => 'delete_customers',
//             'delete_others_posts' => 'delete_others_customers',
//             'read_private_posts' => 'read_private_customers',
//             'edit_post' => 'edit_customer',
//             'delete_post' => 'delete_customer',
//             'read_post' => 'read_customer',
//         ),
//         'supports'            => array(
//             'title', 'editor', 'author', 'thumbnail',
//             'excerpt','custom-fields', 'trackbacks', 'comments',
//             'revisions', 'page-attributes', 'post-formats'
//             )
//     );

//     register_post_type( 'cpt-customers', $args );
// }

// add_action( 'init', 'prefix_register_name' );



function create_my_post_types() {
    register_post_type(
        'cpt_student',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Students',
            // 'capability_type' => 'child',
            // 'capabilities' => array(
            //     'publish_posts' => 'publish_childs',
            //     'edit_posts' => 'edit_childs',
            //     'edit_others_posts' => 'edit_others_childs',
            //     'delete_posts' => 'delete_childs',
            //     'delete_others_posts' => 'delete_others_childs',
            //     'read_private_posts' => 'read_private_childs',
            //     'edit_post' => 'edit_child',
            //     'delete_post' => 'delete_child',
            //     'read_post' => 'read_child',
            // ),
            'supports' => array( '' ),
        )
    );

    register_post_type(
        'cpt_interest',
        array(
            'public' => false,
            'show_ui' => true,
            'label' => 'Interests',
            'taxonomies' => array('interest_type'),
            // 'capability_type' => 'interest',
            // 'capabilities' => array(
            //     'publish_posts' => 'publish_interests',
            //     'edit_posts' => 'edit_interests',
            //     'edit_others_posts' => 'edit_others_interests',
            //     'delete_posts' => 'delete_interests',
            //     'delete_others_posts' => 'delete_others_interests',
            //     'read_private_posts' => 'read_private_interests',
            //     'edit_post' => 'edit_interest',
            //     'delete_post' => 'delete_interest',
            //     'read_post' => 'read_interest',
            // ),
            'supports' => array( 'title' ),
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
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'has_archive' => true,
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
