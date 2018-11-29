<?php 
session_start();

include("database.php");

function login($data){

	$username = $data->data->username;
	$password = $data->data->password;
	$usertype = $data->data->usertype;
	$source = $data->source;

	$DB = new Database($usertype);

	if($usertype == 'users'){
		$userdata = $DB->getData(['id','name', 'user_type_id'], ['username' => $username, 'password' => hashPassword($password)]);
		$name = $userdata[0]['name'];
		$userid = $userdata[0]['id'];
		$user_type = $userdata[0]['user_type_id'];
	}
	
	if($usertype == 'establishments'){
		$userdata = $DB->getData(['id','name'], ['cnpj' => $username, 'password' => hashPassword($password)]);
		$name = $userdata[0]['name'];
		$userid = $userdata[0]['id'];
		$user_type = 'estabelecimento';
	}

	$result = array();

	if(count($userdata) == 1){

		if($source == 'mobile'){
			$result['token'] = session_id();
			$result['name'] = $name;
			$result['userid'] = $userid;
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

	print_r(json_encode($result));
	
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

?>