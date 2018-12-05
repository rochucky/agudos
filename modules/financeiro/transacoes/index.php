      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-dollar"></li>
                  Transações
                </div>
                <div class="col-md-6 text-right mod-buttons">
                  <button type="button" class="btn btn-primary menu-btn menu-btn-status-change" data-content="table-responsive" data-toggle="modal" data-target="#changeStatusModal">Alterar Status</button>
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="transactions" data-join="[>]users|user_id|id:[>]establishments|establishment_id|id" data-filters="" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="transactions.code">Código</th>
                      <th data-field="transactions.value">Valor</th>
                      <th data-field="transactions.date">Data</th>
                      <th data-field="users.name">Funcionário</th>
                      <th data-field="establishments.name">Estabelecimento</th>
                      <th data-field="transactions.status">Status</th>
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
          
        <!-- Modal de alteração de status -->
        <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" >Alteração de Status</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="status-change-form">
                  <input type="hidden" name="id">
                  <select class="form-control" name="status" id="status">
                    <option value="1">Aprovada</option>
                    <option value="2">Cancelada</option>
                  </select>
                </form>
              </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary btn-status-change" data-dismiss="modal">Alterar</button>
              </div>
            </div>
          </div>
        </div>
        

        <script>
          var afterLoad = function(datatable){
            datatable.destroy();
            $('tr td span[name="transactions.date"]').each(function(){
              var dateTime = $(this).html().split(' ');
              var datePart = dateTime[0].split('-').reverse().join('/');
              $(this).html(datePart + ' ' + dateTime[1]);
            });
          
          var status = {
              1: "Aprovada",
              2: "Cancelada"
            };

            $('tr td span[name="transactions.status"]').each(function(){
              $(this).html(status[$(this).html()]);
            });

          datatable = $('#dataTable').DataTable({
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
                },
                select: {
                  rows: ""
                }
              },
              "select": true
            });


            $('tr').off('dblclick');

            $('.btn-status-change').click(function(){
              var rowId = datatable.rows( { selected: true } ).ids()[0];
              var status = $('select[name=status]').val();
              var code = $('tr#'+rowId+' td span[name="transactions.code"]').html();
              if(rowId == undefined){
                notification("Nenhum registro selecionado", 'error');
                return false;
              }
              customConfirm({
                text: "Deseja realmente alterar esta transação?",
                yesFunction: function(){

                  var data = {
                    "table": 'transactions',
                    "filter": "code|"+code,
                    "data": {
                      "status": status,
                      "id": "custom"
                    },
                    "method": "saveData"
                  };

                  data = JSON.stringify(data);

                  $.ajax({
                    url: 'webservice.php',
                    method: 'POST',
                    datatype: 'json',
                    data: {data: data},
                    success: function(response){
                      console.log(response);
                      notification('Transação alterada.');
                      loadModule('financeiro/transacoes');
                    }
                  });
                }
              });
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

        