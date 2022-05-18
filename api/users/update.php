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

/*$form_data = file_get_contents('php://input');

$request = json_decode($form_data, true);*/
$response = array('status'=>0, 'message'=>'');
$auth = getAuthorization();

if(isset($auth['success'])){
	
	if(isset($_GET['action'])){

		$data = $_POST;
		$request = $_GET;

		if ($request['action']=='add') {
				$required = array('username','num_serie','date_debut','date_fin','status');
				$missing = array_diff_key(array_flip($required), $data);
				if(count($missing)==0){
					$idAlreadyExist = !empty(($users->selects('username',$data['username'])));
					$data = $users->setDefaultValue($data);
					if(strtotime($data['date_debut']) > strtotime($data['date_fin'])){
						http_response_code(400);
						$response['status'] = 400;
						$response['message'] = 'La date début de licence doit être inférieur à la fin du licence';
						echo json_encode($response);
						return;
					}
					if(!$idAlreadyExist){
						if($users->add($data)){
							$response['status'] = 200;
							$response['message'] = 'Ajouté avec succès';
						} else {
							http_response_code(500);
							$response['status'] = 500;
							$response['message'] = 'Erreur liés à la base de données';
						}
					} else {
						http_response_code(409);
						$response['status'] = 409;
						$response['message'] = 'Nom d\'utilisateur existe déjà';
					}

				} else {
					http_response_code(400);
					$response['status'] = 400;
					$response['message'] = "Veuillez compléter le(s) champ(s) requis : 'username','num_serie','date_debut','date_fin','status'";
				}
			

		} elseif ($request['action']=='update') {
			$required = array('id','username','num_serie','date_debut','date_fin','status');
			$missing = array_diff_key(array_flip($required), $data);

			if(count($missing)==0){
				$idAlreadyExist = ($users->select($data['id']) != null);

				if($idAlreadyExist){
					if($users->update($data)){
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
				$response['message'] = "Veuillez compléter le(s) champ(s) requis : 'id','username','num_serie','date_debut','date_fin','status'";
			}

		} elseif ($request['action']=='delete') {
			if(isset($data['id']) || isset($request['id'])){

				$id = isset($data['id']) ? $data['id'] : $request['id'];
				$idAlreadyExist = ($users->select($id) != null);
				if($idAlreadyExist){
					if($users->delete($id)){
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
				if($users->deleteMultiple($id)){
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