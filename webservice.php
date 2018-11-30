<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET,  OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include('classes/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

$data = json_decode($_POST['data']);

if($data == ''){
	$data = file_get_contents("php://input");
	$data = json_decode($data)->credentials;
}

if($data->source == "mobile"){

}
else{
	validateSession();
	if(!isset($_SESSION['sessid'])){
	die('session_error');
}
}

call_user_func($data->method, $data);

function parseConditions($string){
	
	if($string == null){
		return null;
	}

	$data = array();

	$parse1 = explode(":", $string);
	foreach($parse1 as $parse1_val){
		$parse2 = explode('|', $parse1_val);
		if(isset($parse2[2])){
			$data[str_replace('-', '_', $parse2[0])] = array(str_replace('-', '_', $parse2[1]) => str_replace('-', '_', $parse2[2]));
		}
		else{
			$data[str_replace('-', '_', $parse2[0])] = $parse2[1];
		}

	}
	return $data;
}

function getTableData($data){

	$table = new Database(str_replace('-', '_', $data->table));
	if(property_exists($data, 'filter')){
		$conditions = parseConditions($data->filter);
	}
	else{
		$conditions = null;
	}

	if(property_exists($data, 'join')){
		$joins = parseConditions($data->join);
	}
	else{
		$joins = null;
	}

	foreach($data->fields as $field){
		$split = explode('.', $field);
		if(count($split) > 1){
			$fields[str_replace('-', '_', $split[0])][] = str_replace('-', '_', $field)." (".str_replace(array('.','-'), array('__','_'), $field).")";
		}
		else{
			$fields[str_replace('-', '_', $data->table)][] = $field;
		}
	}

	$results = $table->getData($fields, $conditions, $joins);

	foreach($results as $result){
		$array[] = json_encode($result);
	}

	print(json_encode($array));

}

function getRecord($data){

	$table = new Database(str_replace('-', '_', $data->table));
	if(property_exists($data, 'filter')){
		$conditions = parseConditions($data->filter);
	}

	if($data->id != ''){
		$conditions['id'] = $data->id;
	}

	$results = $table->getData('*', $conditions, '');

	foreach($results as $result){
		$record = json_encode($result);
	}

	print($record);
}

function saveData($data){

	$_data = $data->data;

	$table = new Database(str_replace('-', '_', $data->table));

	if($data->filter != ''){
		$conditions = parseConditions($data->filter);
		foreach($conditions as $condition_key => $condition_val){
			$_data->filter = $conditions;
		}
	}
	else{
		$conditions = null;
	}

	$objVars = get_object_vars($_data);
	foreach($objVars as $key => $val){
		if(strstr($key, '-')){
			$newKey = str_replace('-', '_', $key);
			$_data->$newKey = $val;
			unset($_data->$key);
		}
	}

	$id = $_data->id;
	unset($_data->id);

	$_data = (array)$_data;
		
	if($id == ''){
		if(isset($_data['password'])){
			$_data['password'] = hashPassword($_data['password']);
		}
		$response = $table->insertData($_data);
	}
	else{
		if(isset($_data['password'])){
			unset($_data['password']);
		}
		$response = $table->updateData($_data, $id);
	}
	
	print(json_encode($response));

}

function deleteData($data){
	
	$table = new Database(str_replace('-', '_', $data->table));
	$response = $table->deleteData($data->id);

	print(json_encode($response));
}

function makeSale($data){
	
	$userTable = new Database('users');
	$balanceTable = new Database('balance');
	$transactionTable = new Database('transactions');

	$userConditions = [
		'username' => $data->cpf,
		'password' => hashPassword($data->password)
	];
	$response = [];
	$userData = $userTable->getData('id', $userConditions, '');
	if(isset($userData[0])){

		if($data->installments == 1){
			$balanceType = 1; // A vista
		}
		else{
			$balanceType = 2; // Parcelado
		}

		$balanceConditions = [
			'user_id' => $userData[0],
			'type' => $balanceType
		];

		$balanceFields = array('value');

		$balanceData = $balanceTable-> getData($balanceFields, $balanceConditions, '');

		$transactionsBalance = getMonthTransactions($transactionTable, $balanceConditions, $balanceType);
		$balance = $balanceData[0]['value'] - $transactionsBalance;

		if(isset($balanceData[0])){
			$data->value = str_replace(array('.',','), array('','.'), $data->value);
			if($balance >= $data->value){
				if($balanceType == 1){
					$transactionData = [
						'establishment_id' => $_SESSION['userid'],
						'user_id' => $userData[0],
						'value' => $data->value,
						'date' => date('Y-m-d H:i:s'),
						'comments' => 'À Vista',
						'status' => 1,
						'code' => date('YmdHis').$_SESSION['userid']
					];

					$response = $transactionTable->insertData($transactionData);
					$response['error'] = '';
					$response['message'] = 'Pagamento efetuado.';
				}
				else{
					$count = 0;
					$value = $data->value/$data->installments;
					$code = date('YmdHis').$_SESSION['userid'];
					do{
						$transactionData = [
							'establishment_id' => $_SESSION['userid'],
							'user_id' => $userData[0],
							'value' => $value,
							'date' => date('Y-m-d', strtotime("+ ".$count. "months")),
							'comments' => ($count+1).'/'.$data->installments,
							'status' => 1,
							'code' => $code
						];

						$response = $transactionTable->insertData($transactionData);
						$response['error'] = '';
						$count++;
					}
					while($count < $data->installments);
					$response['message'] = 'Pagamento efetuado<br>Criadas '.$count.' parcelas de R$ '.number_format($value,2,",",".").' cada.';
				}
			}
			else{
				$response['error'] = 'balance_not_enough';
				$response['balance'] = $balance;
			}
		}
		else{
			$response['error'] = 'balance_not_found';
		}
	}
	else{
		$response['error'] = 'bad_password';
	}

	print(json_encode($response));

}

?>