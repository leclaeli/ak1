<?php
    $interests = get_query_var( 'ai' ); // associated_interests
    $daysofweek = get_query_var( 'dow' ); // day of week
    $start_date = get_query_var( 'sd' ); // start date 
    $prog_orgs = get_query_var( 'org' ); // end date
    $age = get_query_var( 'age' ); 
    $user_address = ( get_query_var( 'addy' ) != 0 ? get_query_var( 'addy' ) : "Milwaukee, WI" );
    $price = get_query_var( 'pr', 0 );
    $experience = get_query_var( 'ex' ); // experience/activity level
    $distance = ( get_query_var( 'di', 9999999 ) != 0 ? get_query_var( 'di' ) : 9999999 ); // distance
    $sr = get_query_var( 'sr' ); // sort results
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
?>

<?php 
    /**
     * The WordPress Query class.
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     *
     */

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cpt_program',
        'meta_query' => array(
            array(
            'relation' => 'OR',
                array (
                    'key' => 'prog_ongoing',
                    'value' => true,
                    'compare' => '=',
                ),
                   
                array (
                    'key' => 'prog_date_start',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),
            ),
        ),
        'meta_key' => $key,
        'orderby' => $order_by,
        'order'   => $order,
        'meta_type' => $type,
    );

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

    if (!empty( $activity_level )) {
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
    }

    if (!empty( $prog_orgs )) {
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
    }

    if (!empty( $price )) {
        array_push($args['meta_query'], array (
            'key' => 'prog_cost',
            'value' => $price,
            'compare' => '<=',
            'type' => 'NUMERIC'
        ));
    }

    if ( !empty( $experience ) && !in_array( "0", $experience ) ) {
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


?>