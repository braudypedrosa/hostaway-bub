<?php

class Hostaway_API_HELPER {

    public function getToken() {
        if(empty(get_option('hostaway_token'))) {
            generateToken();
        } 

        return get_option('hostaway_token');
    }
}