<?php 
session_start();

include("database.php");

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

function login($data){

	$username = $data->data->username;
	$password = $data->data->password;
	$usertype = $data->data->usertype;
	$source = $data->source;

	$DB = new Database($usertype);

	if($usertype == 'users'){
		$userdata = $DB->getData(['id','name', 'user_type_id', 'is_active'], ['username' => $username, 'password' => hashPassword($password)]);
		$name = $userdata[0]['name'];
		$userid = $userdata[0]['id'];
		$user_type = $userdata[0]['user_type_id'];
	}

	if($usertype == 'establishments'){
		$userdata = $DB->getData(['id','name', 'is_active'], ['cnpj' => $username, 'password' => hashPassword($password)]);
		$name = $userdata[0]['name'];
		$userid = $userdata[0]['id'];
		$user_type = 'estabelecimento';
	}
	
	$result = array();

	
	if(count($userdata) == 1){

		if($userdata[0]['is_active'] != 1){
			$result['error'] = true;
			$result['message'] = "Usuário inativo";
			print_r(json_encode($result));
			return false;
		}

		if($source == 'mobile'){
			$result['token'] = session_id();
			$result['name'] = $name;
			$result['userid'] = $userid;
			$result['usertype'] = $usertype;
		}
		else{
			$_SESSION['sessid'] = session_id();
			$_SESSION['name'] = $name;
			$_SESSION['userid'] = $userid;
			$_SESSION['time'] = time();

			if($user_type == 'estabelecimento'){
				$_SESSION['usertype'] = $user_type;
			}
			else{

				$usertypeTable = new Database('user_types');
				$user_type = $usertypeTable->getData(['name'], ['id' => $user_type]);
				$_SESSION['usertype'] = $user_type[0]['name'];
			}
		}
		
	}
	else{
		$result['error'] = true;
		$result['message'] = "Usuário ou senha incorretos";
	}

	if($source == 'mobile'){
		print_r(json_encode($result));
	}
	else{
		return $result;
	}
	
}

function logout(){
	unset($_SESSION['sessid']);
}

function validateSession(){
	if(time() - $_SESSION['time'] > 1800){
		$_SESSION = array();
	}
	else{
		$_SESSION['time'] = time();	
	}
}

function hashPassword($password){

	$options = [
		'cost' => 12,
		'salt' => 'tYNzbWiPsoX2D/TPqv14DumZvMY'
	];

	$result = password_hash($password, PASSWORD_BCRYPT, $options);

	return $result;

}

function getMonthTransactions($transactionTable, $conditions, $balanceType){



	$fields = array('value');
	$conditions = array('user_id' => $conditions['user_id'], 'date[>=]' => date('Y-m-01'), 'status' => 1);
	if($balanceType == 1){
		$conditions['comments'] = 'À vista';
	}
	else{
		$conditions['comments[!]'] = 'À vista';	
	}

	$result = $transactionTable->getData($fields, $conditions, '');

	$value = 0;

	foreach($result as $line){
		$value += $line['value'];
	}

	return $value;
	
}

function changePassword($data){

	if($data->data->oldPassword){
		if($_SESSION['usertype'] == 'estabelecimento'){
			$usersTable = new Database('establishments');
		}
		else{
			$usersTable = new Database('users');
		}
		
		$usersData = $usersTable->getData(array('password'), array('id' => $_SESSION['userid']), '');
		
		foreach($usersData as $user){
			if(hashPassword($data->data->oldPassword) != $user['password']){
				$result['error'] = true;
				$result['message'] = 'Senha incorreta';
			}else{
				$updData = array('password' => hashPassword($data->data->password));
				$result = $usersTable->updateData($updData, $_SESSION['userid']);
			}
		}
	}
	else{
		$usersTable = new Database($data->table);
		$updData = array('password' => hashPassword($data->data->password));
		$result = $usersTable->updateData($updData, $data->id);
	}

	
	print(json_encode($result));
}

function getBalanceData($data){

	$userid = $data->data->userid;

	$balanceTable = new Database('balance');
	$transactionTable = new Database('transactions');

	$balanceData = $balanceTable->getData(array('value', 'type'), array('user_id' => $userid));
	foreach($balanceData as $balanceLine){
		if($balanceLine['type'] == 1){
			$balanceAvista = $balanceLine['value'];
		}
		else{
			$balanceParcelado = $balanceLine['value'];
		}
	}

	$transactionData = $transactionTable->getData(
			array(
				'transactions' => array('value', 'comments', 'status', 'date'),
				'establishments' => array('name')
			),  // Campos
			array('user_id' => $userid, 'status' => 1, 'date[>=]' => date('Y-m-01'), 'ORDER' => array('date' => 'ASC') ),  // Where
			array('[>]establishments' => array('transactions.establishment_id' => 'id'))); // Join
	
	$currentDebt = 0;
	$futureDebt = 0;
	$establishmentData = array();

	foreach($transactionData as $transactionLine){
		if($transactionLine['transactions']['comments'] == 'À Vista'){
			$balanceAvista -= $transactionLine['transactions']['value'];
		}
		else{
			$balanceParcelado -= $transactionLine['transactions']['value'];

		}
		if(date('m', strtotime($transactionLine['transactions']['date'])) == date('m')){
			$currentDebt += $transactionLine['transactions']['value'];
			$establishmentData[] = array(
				'name' => $transactionLine['establishments']['name'],
				'value' => $transactionLine['transactions']['value'],
				'date' => date('d/m/Y H:i', strtotime($transactionLine['transactions']['date'])),
				'type' => $transactionLine['transactions']['comments']
			);
		}
		else{
			$futureDebt += $transactionLine['transactions']['value'];
		}
	}

	$response = array(
		'avista' => round($balanceAvista, 2),
		'parcelado' => round($balanceParcelado, 2),
		'atual' => round($currentDebt, 2),
		'futuro' => round($futureDebt, 2),
		'establishment' => $establishmentData,
		'error' => false
	);

	print_r(json_encode($response));
}

function getEstablishments($data){

	$userid = $data->data->userid;

	$establishmentsTable = new Database('establishments');

	$establishmentsData = $establishmentsTable->getData(
		array('name','address'),
		'',
		''
	);

	print_r(json_encode($establishmentsData));
}

function getUserTransactions($data){

	$userid = $data->data->userid;

	$transactionsTable = new Database('transactions');

	$transactionsData = $transactionsTable->getData(
		array(
			'transactions' => array('transactions.code','date','value','comments'),
			'establishments' => array('name')),
		array('transactions.user_id' => $userid, 'transactions.status' => 1, 'date[>=]' => date('Y-m-01'), 'ORDER' => array('transactions.date' => 'DESC')),
		array('[>]establishments' => array('transactions.establishment_id' => 'id'))
	);
	
	if($transactionsData == null){
		$response['error'] = true;
		$response['message'] = "Nenhum dado encontrado";
	}
	else{

		foreach($transactionsData as $val){
			$response[] = array(
				'code' => $val['transactions']['code'],
				'date' => date('d/m/Y H:i', strtotime($val['transactions']['date'])),
				'name' => $val['establishments']['name'],
				'value' => $val['transactions']['value'],
				'type' => $val['transactions']['comments'],
				'visibility' => ($val['transactions']['date'] <= date('Y-m-d', strtotime('tomorrow'))) ? '' : 'hide'
			);
		}
	}

	print_r(json_encode($response));
}

function getEstablishmentTransactions($data){

	$userid = $data->data->userid;

	$transactionsTable = new Database('transactions');

	$transactionsData = $transactionsTable->getData(
		array(
			'transactions' => array('transactions.code','date','value','comments','status'),
			'users' => array('name')),
		array('transactions.establishment_id' => $userid, 'date[>=]' => date('Y-m-01'), 'ORDER' => array('transactions.date' => 'DESC')),
		array('[>]users' => array('transactions.user_id' => 'id'))
	);

	if($transactionsData == null){
		$response['error'] = true;
		$response['message'] = "Nenhum dado encontrado";
	}
	else{

		foreach($transactionsData as $val){
			$response[] = array(
				'code' => $val['transactions']['code'],
				'date' => date('d/m/Y H:i', strtotime($val['transactions']['date'])),
				'name' => $val['users']['name'],
				'value' => $val['transactions']['value'],
				'type' => $val['transactions']['comments'],
				'status' => ($val['transactions']['status'] == 1) ? '' : 'Cancelada',
				'cancelButton' => ($val['transactions']['status'] == 1) ? '' : 'hide'
			);
		}
	}

	print_r(json_encode($response));
}

?>