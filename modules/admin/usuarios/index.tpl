      

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

        <script>
          var preSubmit = function(){
            
          }

          var resetPassword = function(){
            notification('Senha resetada...');
          }

          addButton('Alterar Senha', resetPassword);

          var afterLoad = function(){

            $('tr td span[name="users.is-active"]').each(function(){
              if($(this).html() == '1'){
                $(this).html('Sim');
              }
              else{
                $(this).html('Não');
              }
            })
          }

        </script>



