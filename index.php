<?php 
date_default_timezone_set('America/Sao_Paulo');

include('classes/functions.php');

validateSession();

if(!isset($_SESSION['sessid'])){
  header('Location: login.php');
}

 ?>
<!DOCTYPE html>
<html>

  <head>
    <script>
      var usertype = '<?= $_SESSION['usertype'] ?>';       
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Prefeitura de Agudos</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="css/select.dataTables.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/noty.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

      <a class="navbar-brand mr-1" href="index.html"></a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar Search -->
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <!-- <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <button class="btn btn-primary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div> -->
      </form>

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Olá, <?= substr($_SESSION['name'],0,strpos($_SESSION['name']," ")); ?>  <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="" data-toggle="modal" data-target="#changePasswordModal">Alterar Senha</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">Sair</a>
          </div>
        </li>
      </ul>

    </nav>

    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav main-menu">
        
        <?php if(in_array($_SESSION['usertype'], array("dev", "admin","funcionario"))): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-folder"></i>
              <span>Cadastro</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
              <a class="dropdown-item menu-item" data-module="cadastro/funcionarios" href="">Funcionários</a>
              <a class="dropdown-item menu-item" data-module="cadastro/estabelecimentos" href="">Estabelecimentos</a>
              <a class="dropdown-item menu-item" data-module="cadastro/saldo" href="">Saldo Mensal</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-folder"></i>
              <span>Admin</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
              <a class="dropdown-item menu-item" data-module="admin/usuarios" href="">Usuários</a>
              <a class="dropdown-item menu-item" data-module="admin/tiposUsuarios" href="">Tipos de Usuários</a>
            </div>
          </li>
        <?php endif; ?>
        <?php if(in_array($_SESSION['usertype'], array("dev", "estabelecimento"))): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-folder"></i>
              <span>Vendas</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
              <a class="dropdown-item menu-item" data-module="vendas/newVenda" data-modtype="custom" href="">Nova Venda</a>
              <a class="dropdown-item menu-item" data-module="vendas/listVendas" href="">Minhas Vendas</a>
            </div>
          </li>
        <?php endif; ?>
        
      </ul>

      
      <div id="content-wrapper">
        
      </div>


    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Desja realmente sair?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <form method="POST" action="login.php">
              <input type="hidden" name="logout" value="true"/>
              <button type="submit" class="btn btn-primary">Sair</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Change Password Modal-->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePasswordModalLabel">Alterar Senha</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="changePasswordForm">
              <label for="oldPassword">Senha Atual</label>
              <input type="password" class="form-control" name="oldPassword"/>
              <label for="newPassword">Nova Senha</label>
              <input type="password" class="form-control" name="newPassword"/>
              <label for="confirmPassword">Confirmar Senha</label>
              <input type="password" class="form-control" name="confirmPassword"/>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-change-password-main" type="button">Enviar</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <script src="js/mo.min.js"></script>
    <script src="js/noty.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/util.js"></script>
    <script src="js/main.js"></script>
  </body>

</html>
