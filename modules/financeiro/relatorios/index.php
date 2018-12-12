

        <div class="container-fluid">

          

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-table"></li>
                  Relatórios
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="form-responsive tab-container">
                <form id="dataForm" novalidate>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="user-type-id">Mês</label>
                      <select class="form-control" name="month" required>
                        <option value="01" selected>Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="type">Ano</label>
                      <select class="form-control" name="year" required>
                        <option value="2018" selected>2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="type">Tipo</label>
                    <select class="form-control" name="type" /required>
                      <option value="" selected></option>
                      <option value="establishments">Estabelecimentos</option>
                      <option value="users">Funcionários</option>
                      <option value="geral">Geral</option>
                    </select>
                  </div>
                  <div class="form-group establishments-form-group hidden">
                    <label for="establishments">Estabelecimento</label>
                    <select class="form-control join" name="establishments" /required>
                    </select>
                  </div>
                  <div class="form-group users-form-group hidden">
                    <label for="users">Funcionario</label>
                    <select class="form-control join" name="users" /required>
                    </select>
                  </div>
                  <div class="form-group">
                    <button class="table-form-submit btn btn-primary generate-report-btn">Gerar Relatório</button>
                    <label name="file-container"></label>
                  </div>
                </form>
              </div>
              
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

        <script>
          $('.generate-report-btn').off('click').on('click', function(evt){
            evt.preventDefault();
            var month = $('select[name=month] option:selected').val();
            var year = $('select[name=year] option:selected').val();
            var type = $('select[name=type] option:selected').val();
            if(type == 'users'){
              var param = $('select[name=establishments] option:selected').val();
            }
            if(type == 'establishments'){
              var param = $('select[name=users] option:selected').val();
            }
            var formData = {
              month: month,
              year: year,
              type: type,
              param: param,
              method: "generateReport"
            };

            formData = JSON.stringify(formData);
            $.post('webservice.php', {data: formData}, function(e){
              response = JSON.parse(e);
              if(response.error == true){
                notification(response.message, 'error');
              }
              else{
                $('label[name=file-container]').html('<a href="'+response.file+'" download>Baixar Arquivo</a>');
                notification(response.message);
              }
            });
          });

          afterLoad = function(){

            $('select[name=type]').change(function(e){
              let type = $(this).children('option:selected').val();
              if(type == 'establishments'){
                $('.users-form-group').removeClass('hidden');
                $('.establishments-form-group').addClass('hidden');
              }
              else if(type == 'users'){
                $('.users-form-group').addClass('hidden');
                $('.establishments-form-group').removeClass('hidden');
              }
              else{
                $('.users-form-group').addClass('hidden');
                $('.establishments-form-group').addClass('hidden');
              }
            });

            $('select.join').each(function(e){

              let jqThat = $(this);
              let that = this;
              let data = {
                "table": this.name,
                "fields": ['name'],
                "filter": 'ORDER|name',
                "method": "getTableData"
              };
              $.post('webservice.php', {data: JSON.stringify(data)}, function(response){
                response = JSON.parse(response);
                for(i in response){
                  if(that.name == 'users'){
                    let userData = JSON.parse(response[i]);
                    let option = '<option value="'+userData.id+'">'+userData.users.name+'</option>';
                    jqThat.append(option);
                  }
                  if(that.name == 'establishments'){
                    let userData = JSON.parse(response[i]);
                    let option = '<option value="'+userData.id+'">'+userData.establishments.name+'</option>';
                    jqThat.append(option);
                  }
                }
              })
              
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

        