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
	if($results == null){
		print_r(json_encode(array(
			'error' => true,
			'message' => 'Usuário não encontrado'
		)));
		return false;
	}
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
			$_data->$condition_key = $condition_val;
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

	$id = ($data->source == 'mobile') ? $data->id : $_SESSION['userid'];

	$userConditions = [
		'code' => $data->code,
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
						'establishment_id' => $id,
						'user_id' => $userData[0],
						'value' => $data->value,
						'date' => date('Y-m-d H:i:s'),
						'comments' => 'À Vista',
						'status' => 1,
						'code' => date('YmdHis').$id
					];

					$response = $transactionTable->insertData($transactionData);
					$response['error'] = '';
					$response['message'] = 'Pagamento efetuado.';
				}
				else{
					$count = 0;
					$value = $data->value/$data->installments;
					$code = date('YmdHis').$id;
					do{
						$transactionData = [
							'establishment_id' => $id,
							'user_id' => $userData[0],
							'value' => $value,
							'date' => ($count == 0) ? date('Y-m-d H:i:s', strtotime("+ ".$count. "months")) : date('Y-m-21', strtotime("+ ".$count. "months")),
							'comments' => ($count+1).'/'.$data->installments,
							'status' => 1,
							'code' => $code
						];

						$response = $transactionTable->insertData($transactionData);
						$response['error'] = '';
						$count++;
					}
					while($count < $data->installments);
					$response['error'] = '';
					$response['message'] = 'Pagamento efetuado<br>Criadas '.$count.' parcelas de R$ '.number_format($value,2,",",".").' cada.';
				}
			}
			else{
				$response['error'] = 'balance_not_enough';
				$response['balance'] = $balance;
				$response['message'] = 'Saldo insuficiente';
			}
		}
		else{
			$response['error'] = 'balance_not_found';
			$response['message'] = 'Não há saldo cadastrado para este CPF';
		}
	}
	else{
		$response['error'] = 'bad_password';
		$response['message'] = "Senha inválida";
	}

	print(json_encode($response));


}

function generateReport($data){
	
	$dateStart = $data->year.'-'.($data->month - 1).'-21';
	$dateEnd = $data->year.'-'.$data->month.'-20 23:59:59';

	
	if($data->type == 'users'){
		$transactions = Database::query("
			select
				users.code as Matricula,
				users.name as Nome, 
				users.cpf as CPF,
				'PADRAO' as Area,
				'07/".date('m/Y', strtotime($dateEnd." + 1 month"))."' as 'Data de Vencimento', 
				sum(transactions.value) as Valor
			from
				transactions
			left join 
				users on users.id = transactions.user_id
			where
				transactions.date between :first and :last
				and transactions.status = 1
			group by
				users.name,
				users.code,
				users.cpf",
				[
					':first' => $dateStart,
					':last' => $dateEnd
				]
			);
		
	}

	if(count($transactions) > 0){
		
		$filepath = 'files/'.$_SESSION['userid'].'/'.$data->type.'_'.date('dmY').'.csv';
		$dirname = dirname($filepath);
		if (!is_dir($dirname))
		{
		    mkdir($dirname, 0755, true);
		}

		$file = fopen($filepath, 'w');
		$header = true;
		foreach($transactions as $transaction){
			if($header){
				fputcsv($file, array_keys($transaction));
				$header = false;	
			}
			foreach($transaction as $key => $val){
				if($key == 'CPF'){
					$transaction[$key] = mask($val, '###.###.###-##');
				}
				if($key == 'Valor'){
					$transaction[$key] = number_format($val,2,",","");
				}
			}
			fputcsv($file, $transaction);
		}

		if(fclose($file)){
			$response['error'] = false;
			$response['file'] = $filepath;
			$response['message'] = "Arquivo gerado com sucesso";
			print_r(json_encode($response));
		}
		else{
			$response['error'] = false;
			$response['message'] = "Falha na geração do arquivo, contacte o administrador do sistema.";
			print_r(json_encode($response));
		}
	}
	else{
		$response['error'] = true;
		$response['message'] = 'Não há dados no período indicado.';
		print_r(json_encode($response));
	}

}



?>