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

/*$form_data = file_get_contents('php://input');

$request = json_decode($form_data, true);*/
$response = array('status'=>0, 'message'=>'');

$auth = getAuthorization();

if(isset($auth['success'])){

	if(isset($_GET['action'])){

		$data = $_POST;
		$request = $_GET;

		if ($request['action']=='update') {
			$required = array('id','id_user','ip_address','id_visitor','date_login');
			$missing = array_diff_key(array_flip($required), $data);

			if(count($missing)==0){
				$idAlreadyExist = ($logs->select($data['id']) != null);

				if($idAlreadyExist){
					if($logs->update($data)){
						$response['status'] = 200;
						$response['message'] = 'Modifié avec succès';
					} else {
						http_response_code(500);
						$response['status'] = 500;
						$response['message'] = 'Erreur liés à la base de données';
					}
				} else {
					http_response_code(404);
					$response['status'] = 404;
					$response['message'] = 'Données non trouvé';
				}

			} else {
				http_response_code(400);
				$response['status'] = 400;
				$response['message'] = "Veuillez compléter le(s) champ(s) requis : 'id','id_user','ip_address','id_visitor','date_login'";
			}

		} elseif ($request['action']=='delete') {
			if(isset($data['id']) || isset($request['id'])){

				$id = isset($data['id']) ? $data['id'] : $request['id'];
				$idAlreadyExist = ($logs->select($id) != null);
				if($idAlreadyExist){
					if($logs->delete($id)){
						$response['status'] = 200;
						$response['message'] = 'Supprimé avec succès';
					} else {
						http_response_code(500);
						$response['status'] = 500;
						$response['message'] = 'Erreur liés à la base de données';
					}
				} else {
					http_response_code(404);
					$response['status'] = 404;
					$response['message'] = 'Identifiant n\'existe pas ou déjà supprimé';
				}

			} else {
				http_response_code(400);
				$response['status'] = 400;
				$response['message'] = 'Parametre identifiant non trouvé';
			}

		} elseif ($request['action']=='delete_multiple') {
			if(isset($data['id']) || isset($request['id'])){
				$id = isset($data['id']) ? $data['id'] : $request['id'];
				if($logs->deleteMultiple($id)){
					$response['status'] = 200;
					$response['message'] = 'Supprimé avec succès';
				} else {
					http_response_code(500);
					$response['status'] = 500;
					$response['message'] = 'Erreur liés à la base de données';
				}
			} else {
				http_response_code(400);
				$response['status'] = 400;
				$response['message'] = 'Parametre identifiant non trouvé';
			}
		} elseif ($request['action']=='clear') {
			if($logs->clearTable()){
				$response['status'] = 200;
				$response['message'] = 'Tous supprimés avec succès';
			} else {
				http_response_code(500);
				$response['status'] = 500;
				$response['message'] = 'Erreur liés à la base de données';
			}
		} else {
			http_response_code(400);
			$response['status'] = 400;
			$response['message'] = 'Veuillez ajouter une paramètre action = {add,update,delete} dans la variable GET';
		}
	} else {
		http_response_code(400);
		$response['status'] = 400;
		$response['message'] = 'Veuillez ajouter une paramètre action = {add,update,delete} dans la variable GET';
	}

} else {
	session_start();
	session_destroy();
	http_response_code(400);
	$response['status'] = 400;
	$response['message'] = $auth['error'];
}
echo json_encode($response);

?>