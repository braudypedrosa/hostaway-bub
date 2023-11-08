<?php 

class Hostaway_API extends Hostaway_API_HELPER {

    private $client_id;
    private $client_secret;

    const ACCES_TOKEN_ENDPOINT = 'https://api.hostaway.com/v1/accessTokens';
    const LISTINGS_ENDPOINT = 'https://api.hostaway.com/v1/listings';

    public function __construct($client_id, $client_secret) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function generateToken(){
        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => self::ACCES_TOKEN_ENDPOINT,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&scope=general',
                CURLOPT_HTTPHEADER => array(
                    "Cache-control: no-cache",
                    "Content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            $result = json_decode($response, true);

            curl_close($curl);

            if($err) {
                throw new Exception($err);
            } else {
                if(isset($result['error'])) {
                    return (['status' => 'fail', 'message' => 'Failed generating token! Please check client credentials.']);
                } else {
                    update_option('hostaway_token', $result['access_token']);
                    update_option('hostaway_token_expiry', $result['expires_in']);

                    return (['status' => 'success', 'message' => 'API token successfully generated!']);
                }
            }


        } catch(Exception $e) {
            echo 'Error: ' .$e->getMessage();
        }
    }

    public function syncProperties() {
        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => self::LISTINGS_ENDPOINT,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                  "Authorization: Bearer ".$this->getToken(),
                  "Cache-control: no-cache"
                ),
              ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            $result = json_decode($response, true);

            curl_close($curl);

            if($err) {
                throw new Exception($err);
            } else {
                if(isset($result['error'])) {
                    return (['status' => $result['status'], 'message' => 'Unable to sync properties! Please check API settings.']);
                } else {
                    
                    foreach($json_result['results'] as $listing) {
                        $listing_data = array(
                            "id" => $listing['id'],
                            "name" => $listing['name'],
                            "internalListingName" => $listing['internalListingName'],
                            "description" => $listing['description'],
                            "houseRules" => $listing['houseRules'],
                            "publicAddress" => $listing['publicAddress'],
                            "price" => $listing['price'],
                            "personCapacity" => $listing['personCapacity'],
                            "lat" => $listing['lat'],
                            "lng" => $listing['lng'],
                            "checkInTimeStart" => $listing['checkInTimeStart'],
                            "checkOutTime" => $listing['checkOutTime'],
                            "bedroomsNumber" => $listing['bedroomsNumber'],
                            "bedsNumber" => $listing['bedsNumber'],
                            "bathroomsNumber" => $listing['bathroomsNumber'],
                            "minNights" => $listing['minNights'],
                            "maxNights" => $listing['maxNights'],
                            "bookingcomPropertyRoomName" => $listing['bookingcomPropertyRoomName'],
                            "listingAmenities" => $listing['listingAmenities'],
                            "listingImages" => $listing['listingImages'],
                            "airbnbListingUrl" => $listing['airbnbListingUrl'],
                            "vrboListingUrl" => $listing['vrboListingUrl'],
                            "googleVrListingUrl" => $listing['googleVrListingUrl']
                        );
                    }

                    return (['status' => $result['status'], 'message' => 'Products successfully synced!']);
                }
            }


        } catch(Exception $e) {
            echo 'Error: ' .$e->getMessage();
        }
    }

    private function generateLocalProperty($listing_data) {
        global $wpdb;

        $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'listing_id' AND meta_value='".$listing_data['listing_id']."'";

        $result = $wpdb->get_results($sql,ARRAY_A);
        $post_id = $result[0]['post_id'];

        // add listing if search returns null
        if($post_id == null) {
            $post_id = wp_insert_post(array(
                'post_title'=> $listing_data['shortname'],
                'post_content' => $listing_data['description'],
                'post_type'=> 'guesty_listings',
                'post_status'=> 'publish'
            ));
        } else { // update logic heregi

        }
    }
}