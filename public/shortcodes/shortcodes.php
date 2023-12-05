<?php 

// Display Properties Shortcode
function display_properties_func( $atts ) {  
    // Shortcode attributes
	$data = shortcode_atts(
		array(
			'group' => '',
            'filter' => true
		),
        $atts, 
        'display_properties'
	);

    $args = array(
        'post_type' => 'hostaway_bub',
        'posts_per_page' => -1,
    );

    if($data['group'] != '') {
        array_push($args, array('tax_query' => array(
            array (
                'taxonomy' => 'groups',
                'field' => 'term_id',
                'terms' => $data['group'],
            )
        )));

        $data['filter'] = false;
    }

    $query = new WP_Query( $args );

    $initialColumns = 3;

    $addGapCount = $initialColumns - ($query->found_posts % $initialColumns);

    if($data['filter']) {
        $groupTerms = get_terms( array( 
            'taxonomy' => 'groups',
            'hide_empty' => true,
        ));

        if(!empty($groupTerms)) {
 
            echo '<ul class="listings-filter">';
            echo '<li class="filter_all" value="all">All('.$query->found_posts.')</li>';
            foreach($groupTerms as $term) {
                echo '<li class="filter_'.$term->slug.'" id="'.$term->term_id.'" value="'.$term->slug.'">'.$term->name.'('.$term->count.')</li>';
            }
            echo '</ul>';

        }
    }

    echo '<div class="hostaway-listings listings-container">';
        echo '<div class="listings-wrapper">';

    while ( $query->have_posts() ) :
        $query->the_post();

        $title = get_the_title();
        $featured_image = get_the_post_thumbnail_url(get_the_ID());
        $guests = get_field('capacity', get_the_ID());
        $bedrooms = get_field('bedrooms', get_the_ID());
        $bathrooms = get_field('bathrooms', get_the_ID());
        $amenities = get_the_terms(get_the_ID(), 'amenities');
        $groupFilter = get_the_terms(get_the_ID(), 'groups');
        $id = get_post_meta(get_the_ID(), 'hostaway_listing_id', true);
        $count = 1;

        $filter_classes = '';

        if(!empty($groupFilter)) {
            foreach($groupFilter as $group) {
                $filter_classes .= ' '. $group->slug;
            }
        }

        if($featured_image == '') {
            $featured_image = plugin_dir_url( __DIR__ ) . 'images/property-dummy-image.png';
        }


        echo '<div class="mix hostaway-listing'.$filter_classes.'" id="hlisting-'.$id.'">';

            echo '<a href="https://zen.holidayfuture.com/listings/'.$id.'"><img src="'.$featured_image.'" alt="'.$title.'"></a>'.
                '<div class="listing-info">'.
                    '<a class="listing-name" href="https://zen.holidayfuture.com/listings/'.$id.'">'.$title.'</a>'.
                   '<div class="listing-meta">'.
                        '<span class="listing-capacity">'.$guests.' guests</span>'.
                        '<span class="listing-bedrooms">'.$bedrooms.' bedrooms</span>'.
                        '<span class="listing-baths">'.$bathrooms.' baths</span>'.
                   '</div>';
                
                echo '<div class="listing-amenities">';
                    foreach($amenities as $amenity) {
                        echo '<div class="listing-amenity">'.$amenity->name.'</div>';
                        if(!($count < 3)) { break; }
                        $count++;
                    }
                echo '</div>';

            echo '</div>';

            if(!empty($groupFilter)) {
                echo '<div class="listing-groups">';
                    foreach($groupFilter as $group) {
                        echo '<div class="listing-group">'.$group->name.'</div>';
                    }
                echo '</div>';
            }

        echo '</div>';

    endwhile;

    // add filler gaps
        for($i = 0; $i < $addGapCount; $i++) {
            echo '<div class="gap"></div>';
        }

        echo '</div>';
    echo '</div>';
    
    wp_reset_postdata();

}

add_shortcode( 'display_properties', 'display_properties_func' );


// Display Image Gallery by ID
function display_gallery_func( $atts ) {
     // Shortcode attributes
	$data = shortcode_atts(
		array(
			'largefirst' => true,
            'grid' => false,
            'columns' => 3,
            'imagestoshow' => 9, 
            'propertyid' => '',
		),
        $atts, 
        'display_gallery'
	);

    $additional_class = '';
    $data_attributes = '';

    
    
    if($data['grid']) {

        $additional_class = ' grid-style';

        $data['largefirst'] = false;
        
        $data_attributes = 'data-gallery-columns="'.$data['columns'].'" data-gallery-imagestoshow="'.$data['imagestoshow'].'"';
    }

    if($data['largefirst']) {
        $additional_class .= ' large-first';

        $data_attributes = 'data-gallery-imagestoshow="5"';
    }

    if($data['propertyid'] == '') {
        echo 'Property ID is required!';
    } else {

        global $wpdb;

        $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'hostaway_listing_id' AND meta_value='".$data['propertyid']."'";

        $result = $wpdb->get_results($sql,ARRAY_A);
        $post_id = isset($result[0]['post_id']) ? $result[0]['post_id'] : '';

        if($post_id == null) {
            echo 'Property not found!';
        } else {
            $images = get_post_meta($post_id, 'stored_images');

            echo '<div class="hostaway-property-gallery'.$additional_class.'" '.$data_attributes.'>';
            foreach($images[0] as $image) {
                echo '<img class="property-image" src="'.$image['url'].'" alt="'.$image['caption'].'" />';
            }
            echo '</div>';
        }
    }

}

add_shortcode('display_gallery', 'display_gallery_func'); 