<?php 

include_once '../config/database.php';
include_once '../class/users.php';
include_once '../class/logs.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:Origin,Content-Type,Accept,Authorization,X-Request-With");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();
$users = new User($db);
/**
 * This file processes the login request and sends back a token response
 * if successful.
 */
$requestMethod = $_SERVER['REQUEST_METHOD'];

// retrieve the inbound parameters based on request type.
switch($requestMethod) {

    case 'POST':
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $num_serie = isset($_POST['num_serie']) ? $_POST['num_serie'] : '';
        $ip_address = isset($_POST['ip_address']) ? $_POST['ip_address'] : '';
        $id_visitor = isset($_POST['id_visitor']) ? $_POST['id_visitor'] : '';
    	$date_login = isset($_POST['date_login']) ? $_POST['date_login'] : '';

    	$returnArray = array();

    	if($id_visitor=='') {
    		$returnArray = array('error' => 'id_visitor ne peut pas être vide!');
    		echo json_encode($returnArray, JSON_PRETTY_PRINT);
    		break;
    	}

    	if($ip_address=='') {
    		$returnArray = array('error' => 'ip_address ne peut pas être vide!');
    		echo json_encode($returnArray, JSON_PRETTY_PRINT);
    		break;
    	}
        
    	if($date_login=='') {
    		$returnArray = array('error' => 'date_login ne peut pas être vide!');
    		echo json_encode($returnArray, JSON_PRETTY_PRINT);
    		break;
    	} elseif (DateTime::createFromFormat('Y-m-d H:i:s', $date_login) === false) {
    		$returnArray = array('error' => 'Format du date invalide!');
    		echo json_encode($returnArray, JSON_PRETTY_PRINT);
    		break;
    	}

        $result = $users->authentification($username,$num_serie);
        if ($result['success']) {
        	$logs = new Log($db);
        	$lastLogin = $logs->selectLast($result['id']);
        	if($lastLogin != null){
        		$date_diff = date_diff(date_create($lastLogin['date_login']),date_create($date_login));
        		$day_diff = intval($date_diff->format('%d'));
        		if($id_visitor != $lastLogin['id_visitor']&& $day_diff < 1){
        			$returnArray = array('error' => 'Identité inconnu!\nVeuillez connecté à votre propre machine ou attendre une jour de plus');
        			echo json_encode($returnArray, JSON_PRETTY_PRINT);
        			break;
        		}
        	}
            require_once('../config/jwt.php');

            /** 
             * Create some payload data with user data we would normally retrieve from a
             * database with users credentials. Then when the client sends back the token,
             * this payload data is available for us to use to retrieve other data 
             * if necessary.
             */
            $userId = 'user'.$result['id'];
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
            $serverKey = '5f2b5cdbe5194f20b3241568fe4e2b24';

            // create a token
            $payloadArray = array();
            $payloadArray['userId'] = $userId;

            if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
            if (isset($exp)) {$payloadArray['exp'] = $exp;}
            $token = JWT::encode($payloadArray, $serverKey);

            $log = array(
				'id_user' => $result['id'],
				'id_visitor' => $id_visitor,
				'ip_address' => $ip_address,
				'date_login' => $date_login
			);
			
            $logs->add($log);

            // return to caller
            $returnArray = array('token' => $token);
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;

        } 
        else {
            $returnArray = array('error' => $result['error']);
            $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
            echo $jsonEncodedReturnArray;
        }

        break;

    case 'GET':

        $token = null;
        
        if (isset($_GET['token'])) {$token = $_GET['token'];}

        if (!is_null($token)) {

            require_once('../config/jwt.php');

            // Get our server-side secret key from a secure location.
            $serverKey = '5f2b5cdbe5194f20b3241568fe4e2b24';

            try {
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                $returnArray = array('userId' => $payload->userId);
                if (isset($payload->exp)) {
                    $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp);
                }
            }
            catch(Exception $e) {
                $returnArray = array('error' => $e->getMessage());
            }
        } 
        else {
            $returnArray = array('error' => 'Please insert valid token');
        }
        
        // return to caller
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;

        break;

    default:
        $returnArray = array('error' => 'You have requested an invalid method.');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;
}