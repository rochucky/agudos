      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-user-tie"></li>
                  Funcionários
                </div>
                <div class="col-md-6 text-right">
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
                    <label for="code">Matricula</label>
                    <input type="number" class="form-control" name="code" required/>
                  </div>
                  <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" required/>
                  </div>
                  <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" name="cpf" required/>
                    <input type="hidden" class="form-control" name="username" />
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" />
                  </div>
                  <div class="form-group">
                    <input type="submit" class="table-form-submit btn btn-success" value="Salvar"/>
                    <button class="table-form-delete btn btn-danger hidden" data-name="name">Excluir</button>
                  </div>
                </form>
              </div>
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="users" data-filters="user-type-id|5" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="code">Matricula</th>
                      <th data-field="name">Nome</th>
                      <th data-field="cpf">CPF</th>
                      <th data-field="email">Email</th>
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

        

        <!-- Scripts do módulo -->
        <script>
          var preSubmit = function(){
            $('input[name=username]').val($('input[name=cpf]').val());
            if($('.table-form-delete.hidden')){
              var html = '<input type="hidden" class="form-control" name="password" value="'+$('input[name=cpf]').val()+'"/>';
              $('#dataForm').append(html);
            }
          }

          $('input[name=cpf]').blur(function(){
            
            $(this).val($(this).val().replace(/[^\d]+/g,''));
          });
        </script>
        