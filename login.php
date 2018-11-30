<?php 
include('classes/functions.php');

if(isset($_POST['logout'])){
  logout();
}

$loginError = '';

if(isset($_POST['username']) && isset($_POST['password'])){

  $obj = new stdClass();
  $obj->data = new stdClass();
  
  $obj->data->username = $_POST['username'];
  $obj->data->password = $_POST['password']; 
  $obj->data->usertype = $_POST['usertype'];

  $loginData = login($obj);

  if(isset($loginData['error'])){
    $loginError = $loginData['message'];
  }
  else{
    header('Location: index.php');
  }

}

if(isset($_SESSION['sessid'])){
  if($_SESSION['sessid'] == session_id()){
    header('Location: index.php');

  }
}

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Login</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

  </head>

  <body class="bg-dark">

    <div class="container">
      <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">
          <form method="POST" action="login.php">
            <div class="row">
              <div class="col-md-12 text-center error-text">
                <?= $loginError ?>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="text" name="username" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                <label for="inputEmail">Usuário</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="required">
                <label for="inputPassword">Senha</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <select name="usertype" id="usertype" class="form-control" required="required">
                  <option value="users">Funcionario</option>
                  <option value="establishments">Estabelecimento</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary form-control">Entrar</button><br><br>
          </form>
          <div class="text-center">
            <a class="d-block small" href="forgot-password.html">Esqueceu a senha?</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  </body>

</html>
