<?php 

class Log
{
	private $connect;
	private $table;
	private $query_select;
	private $query_join;

	function __construct($db)
	{
		$this->connect = $db;
		$this->table = 'log,user';
		$this->query_select = 'log.id,id_user,username,ip_address,id_visitor,date_login';
		$this->query_join = 'log.id_user=user.id';
	}

	function selectAll(){

		$query = "SELECT $this->query_select FROM $this->table WHERE $this->query_join ORDER BY log.id DESC";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function select($id){

		$query = "SELECT $this->query_select FROM $this->table WHERE $this->query_join AND log.id=$id";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetch(PDO::FETCH_ASSOC);

		return $data;
	} 

	function selects($key, $value){

		$query = "SELECT $this->query_select FROM $this->table WHERE $this->query_join AND $key='$value'";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function selectArray($data){

		$query = "SELECT $this->query_select FROM $this->table WHERE $this->query_join ".$this->stringAndWhere($data);
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetchAll(PDO::FETCH_ASSOC);

		return $data;
	} 

	function add($data) {
		$form_data = array(
			':id_user' => $data['id_user'],
			':id_visitor' => $data['id_visitor'],
			':ip_address' => $data['ip_address'],
			':date_login' => $data['date_login']
		);

		$query = "INSERT INTO log (id_user, id_visitor, ip_address, date_login) VALUES (:id_user, :id_visitor, :ip_address, :date_login)";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function update($data){
		$form_data = array(
			':id' => $data['id'],
			':id_user' => $data['id_user'],
			':id_visitor' => $data['id_visitor'],
			':ip_address' => $data['ip_address'],
			':date_login' => $data['date_login']
		);

		$query = "UPDATE log SET id_user=:id_user, id_visitor=:id_visitor, ip_address=:ip_address,date_login=:date_login WHERE id=:id";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function delete($id){
		$form_data = array(
			':id' => $id
		);
		$query = "DELETE FROM log WHERE id=:id";

		$statement = $this->connect->prepare($query);
		return $statement->execute($form_data);
	}

	function deleteMultiple($id){
		$query = "DELETE FROM log WHERE id IN ($id)";

		$statement = $this->connect->prepare($query);
		return $statement->execute();
	}

	function clearTable(){
		$query = "TRUNCATE log";

		$statement = $this->connect->prepare($query);
		return $statement->execute();
	}

	function stringAndWhere($data){
		$str = " AND";
		$i=0;

		foreach ($data as $key => $value) {
			$str .= " $key LIKE '%$value%'";
			if($i < count($data)-1)
				$str .= " AND";
			$i++;
		}

		return $str;
	}

	function selectLast($id){

		$query = "SELECT * FROM log WHERE id_user=$id ORDER BY id DESC";
		$statement  =  $this->connect->prepare($query);
		$statement -> execute();

		$data = $statement -> fetch(PDO::FETCH_ASSOC);

		return $data;
	} 

	function setDefaultValue($data){
		if ($data['date_login']=='') {
			$data['date_login'] = date("Y-m-d H:i:s");
		}

		return $data;

	}

}

?>