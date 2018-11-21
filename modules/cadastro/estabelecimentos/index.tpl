      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-home"></li>
                  Estabelecimentos
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
                      <th data-field="email">Email</th>
                      <th data-field="description">Descritivo</th>
                      <th data-field="cnpj">CNPJ</th>
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
            if($('.table-form-delete.hidden')){
              var html = '<input type="hidden" class="form-control" name="password" value="'+$('input[name=cnpj]').val()+'"/>';
              $('#dataForm').append(html);
            }
          }



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

        