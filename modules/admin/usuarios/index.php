      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-cog"></li>
                  Usuários
                </div>
                <div class="col-md-6 text-right mod-buttons">
                  <button type="button" class="btn btn-primary menu-btn-new" data-content="table-responsive">Novo</button>
                  <button type="button" class="btn btn-primary menu-btn-back hidden" data-content="table-responsive">Voltar</button>
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="form-responsive tab-container hidden">
                <form id="dataForm" novalidate>
                  <input type="hidden" name="id" value="" />
                  <div class="form-group">
                    <label for="code">Código</label>
                    <input type="text" class="form-control" name="code" required/>
                  </div>
                  <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" required/>
                  </div>
                  <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" class="form-control" name="username" required/>
                  </div>
                  <div class="form-group">
                    <label for="user-type-id">Tipo</label>
                    <select class="form-control join" name="user-type-id" data-join="user-types" data-field="name" required> 
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="is-active">Ativo?</label>
                    <select class="form-control" name="is-active" required> 
                      <option value="1">Sim</option>
                      <option value="0">Não</option>
                    </select>
                  </div>

                  <!-- Botões -->
                  <div class="form-group">
                    <input type="submit" class="table-form-submit btn btn-success" value="Salvar"/>
                    <button class="table-form-delete btn btn-danger hidden" data-name="name">Excluir</button>
                  </div>
                </form>
              </div>
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="users" data-filters="" data-join="[>]user-types|user-type-id|id" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="users.code">Codigo</th>
                      <th data-field="users.name">Nome</th>
                      <th data-field="users.username">Usuário</th>
                      <th data-field="user-types.name">Tipo</th>
                      <th data-field="users.is-active">Ativo?</th>
                      
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

        <!-- Password Modal -->
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
            
          }

          var resetPassword = function(){
            $('#passwordModal').modal('show');

            $('.btn-change-password').off('click').click(function(){

              var newPassword = $('input[name=newPassword]').val();
              var confirmPassword = $('input[name=confirmPassword]').val();

              if(newPassword == confirmPassword){
                var data = {
                  "table": "users",
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
                    loadModule('admin/usuarios');
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

          var afterLoad = function(datatable){

            datatable.destroy();

            $('tr td span[name="users.is-active"]').each(function(){
              if($(this).html() == '1'){
                $(this).html('Sim');
              }
              else{
                $(this).html('Não');
              }
            })

            $('#dataTable').DataTable({
              "language": {
                "search": "Buscar: ",
                "lengthMenu": "Exibir _MENU_ registros por página",
                "zeroRecords": "Nenhum registro disponível",
                "info": "Exibindo _TOTAL_ registros",
                "infoEmpty": "Nenhum registro encontrado",
                "infoFiltered": "de _MAX_",
                "paginate": {
                  "first":      '<<',
                  "last":       '>>',
                  "next":       '>',
                  "previous":   '<'
                }
              }
            });

            
          }

        </script>



