<?php 
session_start();

include("database.php");

function login($username, $password, $usertype){

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
	else{
		$result['error'] = 'Usuário ou senha inválidos';
	}

	return $result;
	
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



?>