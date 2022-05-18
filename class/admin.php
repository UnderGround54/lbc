<?php 

class Admin
{
	private $connect;
	private $table;

	function __construct($db)
	{
		$this->connect = $db;
		$this->table = 'admin';
	}

	function selectAll(){

		$query = "SELECT * FROM $this->table ORDER BY id ASC";
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
			':login' => $data['login'],
			':password' => $data['password']
		);

		$query = "INSERT INTO $this->table (login, password) VALUES (:login, :password)";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function update($data){
		$form_data = array(
			':id' => $data['id'],
			':login' => $data['login'],
			':password' => $data['password']
		);

		$query = "UPDATE $this->table SET login=:login, password=:password WHERE id=:id";

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

	function authentification($login, $password){
		$find = -1;
		$query = "SELECT * FROM $this->table WHERE login = '$login'  AND password = '$password'";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetch(PDO::FETCH_ASSOC);

		if($data!=null){
			$find = $data['id'];
		}
		return $find;
	}

}

?>