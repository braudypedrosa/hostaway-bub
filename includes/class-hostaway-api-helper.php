<?php

class Hostaway_API_HELPER {

    public function getToken() {
        if(empty(get_option('hostaway_token'))) {
            generateToken();
        } 

        return get_option('hostaway_token');
    }

    public function imageArrayToMedia($images, $post_id, $selector) {
        
        $imageIDArray = array();
        
        if(isset($post_id)) {
            // loop through the array of images from API
            foreach($images as $image) {

                $caption = $image['caption'];
                $url = $image['url'];


                // $imageID = $this->getMediaFromURL($post_id, $url, $image_id, $caption);
                // array_push($imageIDArray, $imageID);
                
            }
            // update_post_meta($post_id, $selector, );
        }
    }

    public function storeImages($images, $post_id, $selector) {
        $imagesArray = array();

        if(isset($post_id)) {
            foreach($images as $image) {
                $imageData = array(
                    'url' => $image['url'],
                    'caption' => $image['caption']
                );

                array_push($imagesArray, $imageData);
            }

            update_post_meta($post_id, $selector, $imageIDArray);
        }
    }

    public function getMediaFromURL($post_id, $url, $image_id, $caption) {

        $imagefile = file_get_contents($url);
        file_put_contents(plugin_dir_path(dirname(__FILE__)) . "temp/image_".$image_id.".jpg", $imagefile);
        $imageURL = plugin_dir_url(dirname(__FILE__)) . "temp/image_".$image_id.".jpg";

        $imageID = media_sideload_image($imageURL, $post_id, $caption, 'id');
        unlink(plugin_dir_path(dirname(__FILE__)) . "temp/image_".$image_id.".jpg");

        return $imageID;
    }

    public function setFeaturedImageFromURL($image_URL, $post_id, $caption = '') {
        $largeImage = str_replace('x_small', 'x_large', $image_URL);
        $image = media_sideload_image( $largeImage, $post_id, $caption ,'id' );
        set_post_thumbnail( $post_id, $image );
    }


    public function insertDataArrayAsTerms($data, $post_id, $taxonomy, $field_selector){

        $dataIDs = array();
    
        if(!empty($data)) {
    
            foreach ($data as $dataItem) {
    
                if(!term_exists($dataItem[$field_selector], $taxonomy)) {
                    // add term
                    $term = wp_insert_term($dataItem[$field_selector], $taxonomy);
                    array_push($dataIDs, $term['term_id']);
                } else {
                    $term = get_term_by('name', $dataItem[$field_selector], $taxonomy);
                    array_push($dataIDs, $term->term_id);
                }
    
            }
        }

        if(isset($post_id)) {
            wp_set_post_terms($post_id, implode(',', $dataIDs), $taxonomy, true);
        }
    
        return $dataIDs;
    }

    public function generateLocalProperty($listing_data) {
        global $wpdb;

        $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'hostaway_listing_id' AND meta_value='".$listing_data['id']."'";

        $result = $wpdb->get_results($sql,ARRAY_A);
        $post_id = isset($result[0]['post_id']) ? $result[0]['post_id'] : '';

        // add listing if search returns null
        if($post_id == null) {
            $post_id = wp_insert_post(array(
                'post_title'=> $listing_data['name'],
                'post_content' => $listing_data['description'],
                'post_type'=> 'hostaway_bub',
                'post_status'=> 'publish'
            ));
        } else { // update logic here

        }

        update_post_meta($post_id, 'hostaway_listing_id', $listing_data['id']);
        update_field('internal_listing_name', $listing_data['internalListingName'], $post_id);
        update_field('price', $listing_data['price'], $post_id);
        update_field('house_rules', $listing_data['houseRules'], $post_id);
        update_field('public_address', $listing_data['publicAddress'], $post_id);
        update_field('latitude', $listing_data['lat'], $post_id);
        update_field('longitude', $listing_data['lng'], $post_id);
        update_field('room_type', $listing_data['bookingcomPropertyRoomName'], $post_id);
        update_field('check-in_time', $listing_data['checkInTimeStart'], $post_id);
        update_field('check-out_time', $listing_data['checkOutTime'], $post_id);
        update_field('bedrooms', $listing_data['bedroomsNumber'], $post_id);
        update_field('bathrooms', $listing_data['bathroomsNumber'], $post_id);
        update_field('beds', $listing_data['bedsNumber'], $post_id);
        update_field('minimum_nights_of_stay', $listing_data['minNights'], $post_id);
        update_field('maximum_nights_of_stay', $listing_data['maxNights'], $post_id);
        update_field('capacity', $listing_data['personCapacity'], $post_id);

        update_field('airbnb_link', $listing_data['airbnbListingUrl'], $post_id);
        update_field('vrbo_link', $listing_data['vrboListingUrl'], $post_id);
        update_field('google_vr_link', $listing_data['googleVrListingUrl'], $post_id);

        $this->setFeaturedImageFromURL($listing_data['thumbnailUrl'], $post_id);
        $this->storeImages($listing_data['listingImages'], $post_id, 'stored_images');

        // insert amenities
        $this->insertDataArrayAsTerms($listing_data['listingAmenities'], $post_id, 'amenities', 'amenityName');

        // insert categories
        $this->insertDataArrayAsTerms($listing_data['listingTags'], $post_id, 'groups', 'name');
    }
}