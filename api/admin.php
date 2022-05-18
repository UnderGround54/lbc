<?php 

include_once '../config/database.php';
include_once '../class/admin.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:Origin,Content-Type,Accept,Authorization,X-Request-With");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);
/**
 * This file processes the login request and sends back a token response
 * if successful.
 */
$requestMethod = $_SERVER['REQUEST_METHOD'];

// retrieve the inbound parameters based on request type.
switch($requestMethod) {

    case 'POST':

        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

    	$returnArray = array();

        $result = $admin->authentification($login,$password);
        if ($result != -1) {
            require_once('../config/jwt.php');
            session_start ();
            /** 
             * Create some payload data with user data we would normally retrieve from a
             * database with admin credentials. Then when the client sends back the token,
             * this payload data is available for us to use to retrieve other data 
             * if necessary.
             */
            $userId = 'admin'.$result;
            $now = time();

            /**
             * Uncomment the following line and add an appropriate date to enable the 
             * "not before" feature.
             */
            // $nbf = strtotime('2021-01-01 00:00:01');

            /**
             * Uncomment the following line and add an appropriate date and time to enable the 
             * "expire" feature.
             */
            $exp = strtotime('+10 minutes',time());
            // Get our server-side secret key from a secure location.
            $serverKey = '5f2b5cdbe5194f20b3241568fe4e2b2451yogfdjjskjezazyfbdjdfkjndskjk';

            // create a token
            $payloadArray = array();
            $payloadArray['userId'] = $userId;

            if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
            if (isset($exp)) {$payloadArray['exp'] = $exp;}
            $token = JWT::encode($payloadArray, $serverKey);

            $_SESSION['userId'] = $token;
            // return to caller
            $returnArray = array('token' => $token);
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;

        } 
        else {
            $returnArray = array('error' => 'Login ou mot de passe incorrect!');
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;
        }

        break;

    case 'GET': 

        if(isset($_GET['action'])){
            $action = $_GET['action'];
            if($action == 'logout'){
                session_start();
                session_destroy();
                echo json_encode(array('success' => 'disconnected'));
            }
        }
        

    break;
        
    default:
        $returnArray = array('error' => 'You have requested an invalid method.');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;
}