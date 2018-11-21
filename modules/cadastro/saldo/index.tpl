      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-dollar-sign"></li>
                  Saldo Mensal
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
                    <label for="user-type-id">Funcionário</label>
                    <select class="form-control join" name="user-id" data-join="users" data-field="name" required> 
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="year">Ano</label>
                    <input type="number" class="form-control" name="year" required/>
                  </div>
                  <div class="form-group">
                    <label for="month">Mês</label>
                    <select class="form-control" name="month" required>
                      <option value="1">Janeiro</option>
                      <option value="2">Fevereiro</option>
                      <option value="3">Março</option>
                      <option value="4">Abril</option>
                      <option value="5">Maio</option>
                      <option value="6">Junho</option>
                      <option value="7">Julho</option>
                      <option value="8">Agosto</option>
                      <option value="9">Setembro</option>
                      <option value="10">Outubro</option>
                      <option value="11">Novembro</option>
                      <option value="12">Dezembro</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="type">Valor</label>
                    <select class="form-control" name="type" required>
                      <option value="1">À vista</option>
                      <option value="2">Parcelado</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="value">Valor</label>
                    <input type="number" class="form-control" name="value" required/>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="table-form-submit btn btn-success" value="Salvar"/>
                    <button class="table-form-delete btn btn-danger hidden" data-name="name">Excluir</button>
                  </div>
                </form>
              </div>
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="balance" data-join="[><]users|user-id|id" data-filters="" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="users.name">Funcionário</th>
                      <th data-field="year">Ano</th>
                      <th data-field="month">Mes</th>
                      <th data-field="type">Tipo</th>
                      <th data-field="value">Saldo</th>
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
          var afterLoad = function(datatable){
            var months = {
              1: "Janeiro",
              2: "Fevereiro",
              3: "Março",
              4: "Abril",
              5: "Maio",
              6: "Junho",
              7: "Julho",
              8: "Agosto",
              9: "Setembro",
              10: "Outubro",
              11: "Novembro",
              12: "Dezembro"
            }
            
            var types = {
              1: "À Vista",
              2: "Parcelado",
            }            

            $('tr td:nth-child(3)').each(function(){
              $(this).html(months[$(this).html()]);
            });
            $('tr td:nth-child(4)').each(function(){
              $(this).html(types[$(this).html()]);
            });

            datatable.destroy()
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

        <!-- Sticky Footer 
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Your Website 2018</span>
            </div>
          </div>
        </footer>-->

        