<?php

include_once '../../config/database.php';
include_once '../../config/authorization.php';
include_once '../../class/logs.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:Origin,Content-Type,Accept,Authorization,X-Request-With");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();
$logs = new Log($db);

$auth = getAuthorization();

if(isset($auth['success'])){
	if(empty($_GET)){
		$data = $logs->selectAll();
		echo json_encode($data);

	} else {

		$required = array('id','id_user','ip_address','id_visitor','date_login');
		$data = array();

		foreach ($_GET as $key => $value) {
			if(in_array($key, $required)){
				$data[$key] = $value;
			}
		}

		if(count($data)==count($_GET)){
			echo json_encode($logs->selectArray($data));
		} else {
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