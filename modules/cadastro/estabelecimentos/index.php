      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-home"></li>
                  Estabelecimentos
                </div>
                <div class="col-md-6 text-right mod-buttons">
                  <button type="button" class="btn btn-primary menu-btn menu-btn-new" data-content="table-responsive">Novo</button>
                  <button type="button" class="btn btn-primary menu-btn menu-btn-back hidden" data-content="table-responsive">Voltar</button>
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="form-responsive tab-container hidden">
                <form id="dataForm" novalidate>
                  <input type="hidden" name="id" value="" />
                  <div class="form-group">
                    <label for="code">Código</label>
                    <input type="number" class="form-control" name="code" required/>
                  </div>
                  <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" required/>
                  </div>
                  <div class="form-group">
                    <label for="description">Descritivo</label>
                    <input type="text" class="form-control" name="description" required/>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" />
                  </div>
                  <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" name="cnpj" required/>
                  </div>
                  <div class="form-group">
                    <label for="address">Endereço</label>
                    <input type="text" class="form-control" name="address"/>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="table-form-submit btn btn-success" value="Salvar"/>
                    <button class="table-form-delete btn btn-danger hidden" data-name="name">Excluir</button>
                  </div>
                </form>
              </div>
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="establishments" data-filters="" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="code">Código</th>
                      <th data-field="name">Nome</th>
                      <th data-field="description">Descritivo</th>
                      <th data-field="cnpj">CNPJ</th>
                      <th data-field="address">Endereço</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

        <!-- Change Password Modal-->
        <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Alterar Senha</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="changePasswordForm">
                  <label for="newPassword">Nova Senha</label>
                  <input type="password" class="form-control" name="newPassword"/>
                  <label for="confirmPassword">Confirmar Senha</label>
                  <input type="password" class="form-control" name="confirmPassword"/>
                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary btn-change-password" type="button">Enviar</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>

        <script>
          var preSubmit = function(){
            if($('.table-form-delete.hidden')){
              var html = '<input type="hidden" class="form-control" name="password" value="'+$('input[name=cnpj]').val()+'"/>';
              $('#dataForm').append(html);
            }
          }

          var resetPassword = function(){
            $('#passwordModal').modal('show');

            $('.btn-change-password').off('click').click(function(){

              var newPassword = $('input[name=newPassword]').val();
              var confirmPassword = $('input[name=confirmPassword]').val();

              if(newPassword == confirmPassword){
                var data = {
                  "table": "establishments",
                  "id": $('input[name=id]').val(),
                  "data": {
                    "password": newPassword
                  },
                  "method": "changePassword"
                };

                data = JSON.stringify(data);

                $.post('webservice.php', {data: data}, function(e){
                  response = JSON.parse(e);
                  if(response[1] == null){
                    $('#passwordModal').modal('hide');
                    notification('Senha alterada.');
                    loadModule('cadastro/estabelecimentos');
                  }
                  else{
                    notiification('Erro ao alterar a senha<br>Contate o administrador do sistema', 'error');
                  }
                })
              }
              else{
                notification('Senha e confirmação não coincidem.', 'error');
              }
            });
          }

          addButton('Alterar Senha', resetPassword);


          $('input[name=cnpj]').blur(function(){
            
            $(this).val($(this).val().replace(/[^\d]+/g,''));
          });
        </script>

        <!-- Sticky Footer 
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Your Website 2018</span>
            </div>
          </div>
        </footer>-->

        