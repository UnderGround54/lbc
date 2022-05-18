<?php

function getAuthorization(){

	$token = getBearerToken();

	if (!is_null($token)) {

            require_once('../../config/jwt.php');

            // Get our server-side secret key from a secure location.
            $serverKey = '5f2b5cdbe5194f20b3241568fe4e2b2451yogfdjjskjezazyfbdjdfkjndskjk';

            try {
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                $returnArray = array('success' => $payload->userId);
            }
            catch(Exception $e) {
                $returnArray = array('error' => 'Token non valide ou expiré ! Veuillez vous reconnecter');
            }
    } 
    else {
        $returnArray = array('error' => 'Veuillez insérer un token valide');
    }
    
    // return to caller
    return $returnArray;
}

function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}