<?php
    $s = get_search_query();
    // $interests = get_query_var( 'ai' ); // associated_interests
    // $daysofweek = get_query_var( 'dow' ); // day of week
    // $start_date = get_query_var( 'sd' ); // start date 
    // $prog_orgs = get_query_var( 'org' ); // end date
    // $age = get_query_var( 'age' ); 
    // $user_address =  get_query_var( 'addy' );
    // $price = get_query_var( 'pr', 0 );
    // $experience = get_query_var( 'ex' ); // experience/activity level
    // $distance = ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ); // distance
    // $sr = get_query_var( 'sr' ); // sort results - not being used

    $keyword = $_GET['s'];

    if(is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        // get students for current user and check against query
        $args = array('post_type' => 'cpt_student', 'post_status' => 'private', 'author' => $user_id, 'posts_per_page' => -1 );                   
        $students = get_posts($args);
        if(!empty($students)) {               
            $st_ids = array();
            foreach ( $students as $id ) {
                array_push( $st_ids, $id->ID );
            }
        }
    }

    if ( isset( $_GET['st'] ) && $_GET['st'] != "" && is_user_logged_in() && in_array( $_GET['st'], $st_ids ) ) {
        $st = $_GET['st'];
        $st_name = get_field( 'student_name', $st );
        $distance = get_field( 'student_distance', $st);
        $experience = ( !empty( get_field( 'student_experience', $st ) ) ? get_field( 'student_experience', $st ) : array() );
        $interests = ( !empty( get_field( 'student_interests', $st ) ) ? get_field( 'student_interests', $st ) : array() );
        $daysofweek = ( !empty( get_field( 'student_days_desired', $st ) ) ? get_field( 'student_days_desired', $st ) : array() );
        $age = asapkids_get_student_age( $st );
        $address = get_user_address();

    } else {
        $st_name = "Student";
        $st = "";
        $interests = get_query_var( 'ai' ); // associated_interests
        $daysofweek = get_query_var( 'dow' ); // day of week
        $start_date = get_query_var( 'sd' ); // start date 
        $prog_orgs = get_query_var( 'org' ); // end date
        $age = get_query_var( 'age' ); 
        $user_address =  get_query_var( 'addy' );
        $price = get_query_var( 'pr', 0 );
        $experience = get_query_var( 'ex' ); // experience/activity level
        $distance = ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ); // distance
    }


/* Sort results - Not currently being used 
    switch ($sr) {
        case "title_za" :
            $order = 'DESC';
            $order_by = 'title';
            break;
        case "title_az" :
            $order = 'ASC';
            $order_by = 'title';
            break;
        case "date" :
            $order = 'ASC';
            $order_by = 'prog_date_start';
            break;
        case "price" :
            $order = 'ASC';
            $order_by = 'prog_cost';
            $key = 'prog_cost';
            $type = 'NUMERIC';
            break;
        default :
            $order = 'ASC';
            $order_by = 'prog_date_start';
    }
*/


    global $wpdb;
    // If you use a custom search form
    // $keyword = sanitize_text_field( $_POST['keyword'] );
    // If you use default WordPress search form
    // $keyword = get_search_query();
    $keyword = '%' . $wpdb->esc_like( $keyword ) . '%'; // Thanks Manny Fleurmond
    // Search in all custom fields
    $post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT post_id FROM {$wpdb->postmeta}
        WHERE meta_value LIKE '%s'
    ", $keyword ) );
    // Search in post_title and post_content
    $post_ids_post = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT ID FROM {$wpdb->posts}
        WHERE post_title LIKE '%s'
        OR post_content LIKE '%s'
    ", $keyword, $keyword ) );
    $post_ids = array_merge( $post_ids_meta, $post_ids_post );

    /**
     * The WordPress Query class.
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     *
     */

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cpt_program',
        'post__in' => $post_ids,
        'paged' => $paged,
        //'post__not_in' => $expired_posts,
        //'s' => $s,
        'meta_query' => array(
            'featured' => array(
                'key' => 'prog_featured', // {$wpdb->postmeta}
            ),
            'start_date' => array(
                'key' => 'prog_date_start',
            ),
            'ongoing' => array(
                'key' => 'prog_ongoing',
            ),
            array(
                'relation' => 'OR',
                array (
                    'key' => 'prog_date_start',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),
                array (
                    'key' => 'prog_date_expires',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),                
                array (
                    'key' => 'prog_ongoing',
                    'value' => true,
                    'compare' => '=',
                ),
            ),
        ),
    );

    /* Sort results - not being used currently 
    // if ( $sr == "price" ) {
    //     array_push( $args['meta_query'], array( 'key' => 'prog_cost', 'type' => 'NUMERIC') );
    // }
    */

    if (!empty( $age )) {
        array_push($args['meta_query'],  array (
                'key' => 'prog_age_min',
                'value' => $age,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ),
            array (
                'key' => 'prog_age_max',
                'value' => $age,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
    }

    if (!empty( $interests )) {
        $i = 0;
        $ints['relation'] = 'OR';
        foreach ($interests as $interest) {
            $ints[$i] = array(
                'key' => 'associated_interests',
                'value' => '"' . $interest . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $ints);
    }

    if (!empty( $daysofweek )) {
        $i = 0;
        $days['relation'] = 'OR';
        foreach ( $daysofweek as $day ) {
            $days[$i] = array(
                'key' => 'prog_days_offered',
                'value' => '"' . $day . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $days );
    }

    /*if (!empty( $activity_level )) {
        $i = 0;
        $levels['relation'] = 'OR';
        foreach ( $activity_level as $level ) {
            $levels[$i] = array(
                'key' => 'prog_activity_level',
                'value' => '"' . $level . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $levels );
    }*/

    /*if (!empty( $prog_orgs )) {
        $i = 0;
        $orgs['relation'] = 'OR';
        foreach ( $prog_orgs as $org ) {
            $orgs[$i] = array(
                'key' => 'prog_organization',
                'value' => '"' . $org . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $orgs );
    }*/

    if (!empty( $price )) {
        array_push($args['meta_query'], array (
            'key' => 'prog_cost',
            'value' => $price,
            'compare' => '<=',
            'type' => 'NUMERIC'
        ));
    }

    if ( !empty( $experience ) && !in_array( "Any", $experience ) ) {
        $i = 0;
        $exp_levels['relation'] = 'OR';
        foreach ( $experience as $exp_level ) {
            $exp_levels[$i] = array(
                'key' => 'prog_activity_level',
                'value' => '"' . $exp_level . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $exp_levels );
    }
    
    add_filter( 'posts_orderby', $func = function ( $orderby, $query ) {
        $start_date = date('Ymd');
        global $wpdb;
        $sr = get_query_var( 'sr' ); // sort results
        if ($sr == "title_az" ) {
            $orderby = 'wp_posts.post_title ASC';
        } elseif  ($sr == "title_za" ) {
            $orderby = 'wp_posts.post_title DESC';
        } elseif  ($sr == "date" ) {
            $orderby = 'mt1.meta_value ASC';
        } elseif  ($sr == "price" ) {
            $orderby = 'CAST(mt3.meta_value AS SIGNED) ASC';
        } else {
            $orderby = $wpdb->prepare(
                "
                CASE
                    WHEN {$wpdb->postmeta}.meta_value THEN CONCAT('A', mt1.meta_value)
                    WHEN mt1.meta_value >= %d THEN CONCAT('B', mt1.meta_value)
                    WHEN mt2.meta_value AND mt1.meta_value THEN CONCAT('C', mt1.meta_value)
                    WHEN mt2.meta_value THEN 'D'
                    ELSE 'Es'
                END ASC
                "
                , $start_date
            );
        }
        return $orderby;
    }, 10, 2 );
    $query = new WP_Query( $args );
    
    remove_filter( 'posts_orderby', $func, 10, 2 ); 	       
?>