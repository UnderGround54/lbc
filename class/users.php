<?php 

class User
{
	private $connect;
	private $table;

	function __construct($db)
	{
		$this->connect = $db;
		$this->table = 'user';
	}

	function selectAll(){

		$query = "SELECT * FROM $this->table ORDER BY id DESC";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function select($id){

		$query = "SELECT * FROM $this->table WHERE id=$id";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetch(PDO::FETCH_ASSOC);

		return $data;
	} 

	function selects($key, $value){

		$query = "SELECT * FROM $this->table WHERE $key='$value'";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function selectArray($data){

		$query = "SELECT * FROM $this->table".$this->stringAndWhere($data);
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	} 

	function add($data) {
		$form_data = array(
			':username' => $data['username'],
			':num_serie' => $data['num_serie'],
			':date_debut' => $data['date_debut'],
			':date_fin' => $data['date_fin'],
			':status' => $data['status']
		);

		$query = "INSERT INTO $this->table (username, num_serie, date_debut, date_fin, status) VALUES (:username, :num_serie, :date_debut, :date_fin,:status)";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function update($data){
		$form_data = array(
			':id' => $data['id'],
			':username' => $data['username'],
			':num_serie' => $data['num_serie'],
			':date_debut' => $data['date_debut'],
			':date_fin' => $data['date_fin'],
			':status' => $data['status']
		);

		$query = "UPDATE $this->table SET username=:username, num_serie=:num_serie, date_debut=:date_debut,date_fin=:date_fin,status=:status WHERE id=:id";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function delete($id){
		$form_data = array(
			':id' => $id
		);
		$query = "DELETE FROM $this->table WHERE id=:id";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function deleteMultiple($id){
		$query = "DELETE FROM $this->table WHERE id IN ($id)";
		$statement = $this->connect->prepare($query);
		return $statement->execute();
	}
	
	function stringAndWhere($data){
		$str = " WHERE";
		$i=0;

		foreach ($data as $key => $value) {
			$str .= " $key LIKE '%$value%'";
			if($i < count($data)-1)
				$str .= " AND";
			$i++;
		}

		return $str;
	}

	function setDefaultValue($data){
		if($data['num_serie']==''){
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    $length = 10;
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    $data['num_serie'] = $randomString;

		}

		if ($data['status']=='') {
			$data['status'] = '0';
		}

		return $data;

	}

	function authentification($username, $num_serie){
		$result = array();
		$query = "SELECT * FROM $this->table WHERE username = '$username'  AND num_serie = '$num_serie'";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetch(PDO::FETCH_ASSOC);

		if($data!=null){
			$query = "SELECT * FROM $this->table WHERE username = '$username'  AND num_serie = '$num_serie' AND (NOW() BETWEEN date_debut AND date_fin)";
			$statement  =  $this->connect->prepare($query);
			$statement -> execute();

			$data = $statement -> fetch(PDO::FETCH_ASSOC);

			if($data != null){
				$query = "SELECT * FROM $this->table WHERE username = '$username'  AND num_serie = '$num_serie' AND status=true";
				$statement  =  $this->connect->prepare($query);
				$statement -> execute();

				$data = $statement -> fetch(PDO::FETCH_ASSOC);

				if($data !=null){
					$result['success'] = true;
					$result['id'] = $data['id'];
				} else {
					$result['success'] = false;
					$result['error'] = "Votre compte est actuellement inactif,\nVeuillez contacter l'administrateur pour l'activer";
				}	
			} else {
				$result['success'] = false;
				$result['error'] = "Votre licence d'utilisation arrive à expiration,\nVeuillez contacter l'administrateur pour la réactivation";
			}
		} else {
			$result['success'] = false;
			$result['error'] = 'Nom d\'utilisateur ou numéro de série incorrecte!';
		}
		return $result;
	}

}

?>