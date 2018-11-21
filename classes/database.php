<?php 

include('Medoo.php');

use Medoo\Medoo;

class Database{

	

	public function __construct($table){
		$this->conn = new Medoo([
		    'database_type' => 'mysql',
		    'database_name' => 'agudos',
		    'server' => 'localhost',
		    'username' => 'root',
		    'password' => 'eucs1234'
		]);

		$this->table = $table;
	}

	public function getData($fields, $conditions = null, $joins = null, $debug = false){
		if($joins != ''){
			if(is_array($fields)){
				$fields[] = $this->table.".id";
			}
			if($debug){
				echo "DEBUG: ";
				echo $this->conn->debug()->select($this->table, $joins, $fields, $conditions);	
				die();
			}
			return $this->conn->select($this->table, $joins, $fields, $conditions);
			
		}
		else{
			if(is_array($fields)){
				$fields[] = $this->table.".id";
			}
			if($debug){
				echo "DEBUG: ";
				echo $this->conn->debug()->select($this->table, $fields, $conditions);	
				die();
			}
			return $this->conn->select($this->table, $fields, $conditions);
		}
	}

	public function insertData($data, $debug = false){
		if($debug){
			echo "DEBUG: ";
			echo $this->conn->debug()->insert($this->table, $data);	
			die();
		}
		$return = $this->conn->insert($this->table, $data);

		return $return->errorInfo();
	}

	public function updateData($data, $id, $debug = false){
		if($debug){
			echo "DEBUG: ";
			echo $this->conn->debug()->update($this->table, $data, array('id' => $id));	
			die();
		}
		$return = $this->conn->update($this->table, $data, array('id' => $id));

		return $return->errorInfo();
	}

	public function deleteData($id, $debug = false){
		if($debug){
			echo "DEBUG: ";
			echo $this->conn->debug()->delete($this->table, array('id' => $id));	
			die();
		}
		$return = $this->conn->delete($this->table, array('id' => $id));

		return $return->errorInfo();
	}

}

?>