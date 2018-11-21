      

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-dollar"></li>
                  Minhas Vendas
                </div>
                <div class="col-md-6 text-right">
                  <button type="button" class="btn btn-primary menu-btn menu-btn-cancel" data-content="table-responsive">Cancelar</button>
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="table-responsive tab-container">
                <table class="table-bordered table-striped table-hover" id="dataTable" data-table="transactions" data-join="[><]users|user-id|id" data-filters=""  width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th data-field="transactions.code">Código</th>
                      <th data-field="date">Data</th>
                      <th data-field="users.name">Comprador</th>
                      <th data-field="value">Valor</th>
                      <th data-field="comments">Parcelas</th>
                      <th data-field="status">Status</th>
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
          var afterLoad = function(datatable){
            datatable.destroy()
            $('tr td:nth-child(2)').each(function(){
              var dateTime = $(this).html().split(' ');
              var datePart = dateTime[0].split('-').reverse().join('/');
              $(this).html(datePart + ' ' + dateTime[1]);
            });

            var status = {
              1: "Aprovada",
              2: "Cancelada"
            };

            $('tr td:nth-child(6)').each(function(){
              $(this).html(status[$(this).html()]);
            });

            $('table tr').off('dblclick');

            
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
                }
              },
              "select": true
            });

            $('.menu-btn-cancel').click(function(){
              var rowId = datatable.rows( { selected: true } ).ids()[0];
              var code = $('tr#'+rowId+' td span[name="transactions.code"]').html();
              notification(code);
              customConfirm({
                text: "Deseja realmente cancelar esta transação?",
                yesFunction: function(){

                  var data = {
                    "table": 'transactions',
                    "filter": "code|"+code,
                    "data": {
                      "status": 2
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
                      notification('Transação cancelada.');
                      loadModule('vendas/listVendas');
                    }
                  });
                }
              });
            });

          }
        </script>
        