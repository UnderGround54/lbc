<?php

include_once '../../config/database.php';
include_once '../../config/authorization.php';
include_once '../../class/users.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:Origin,Content-Type,Accept,Authorization,X-Request-With");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();
$users = new User($db);

$auth = getAuthorization();

if(isset($auth['success'])){

	if(empty($_GET)){
		$data = $users->selectAll();
		echo json_encode($data);

	} else {

		$required = array('id','username','num_serie','date_debut','date_fin','status');
		$data = array();

		foreach ($_GET as $key => $value) {
			if(in_array($key, $required)){
				$data[$key] = $value;
			}
		}

		if(count($data)==count($_GET)){
			echo json_encode($users->selectArray($data));
		} else {
			http_response_code(404);
			$response['status'] = 404;
			$response['message'] = 'Erreur : Paramètres invalide!';
			echo json_encode($response);
		}
	}

} else {
	session_start();
	session_destroy();
	http_response_code(400);
	$response['status'] = 400;
	$response['message'] = $auth['error'];
	echo json_encode($response);
}

?>