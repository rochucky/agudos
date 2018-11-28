        <div class="container-fluid">

          

          
          <div class="card mb-3">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 title">
                  <li class="fas fa-dollar-sign"></li>
                  Nova venda
                </div>
              </div>
              
            </div>
            <div class="card-body">
              <div class="form-responsive tab-container">
                <div class="row">
                  <div class="col-lg-3"></div>
                  <div class="col-lg-6">
                    
                    <form id="saleForm" novalidate>
                      <div class="form-group first md-col-6">
                        <label for="value">Valor</label>
                        <input type="text" class="form-control" name="value" /> 
                      </div>
                      <div class="form-group first md-col-6">
                        <label for="installments">Parcelas</label>
                        <select class="form-control" name="installments" >
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                        </select> 
                      </div>
                      <div class="form-group first">
                        <label for="cpf">CPF</label>
                        <input type="number" class="form-control" name="cpf" /> 
                      </div>
                      
                      <div class="form-group second">
                        <label for="password">Nome: <span class="name"></span></label><br>
                        <label for="password">CPF: <span class="cpf"></span></label><br>
                        <label for="password">Valor: R$ <span class="value"></span></label>
                        <input type="password" class="form-control" name="password" placeholder="Senha" /> 
                      </div>
                      <div class="form-group first">
                        <button class="btn btn-primary btn-block sale-btn-next">Avançar</button> 
                      </div>
                      <div class="form-group second">
                        <button class="btn btn-primary btn-block sale-btn-submit">Concluir</button>
                        <button class="btn btn-secondary btn-block sale-btn-back">Voltar</button>
                      </div>
                    </form>
                  </div>
                  
                  
                  <div class="col-lg-3"></div>
                </div>
              </div>
          </div>

        </div>
        <!-- /.container-fluid -->

        <script>
          
          var cpf;
          var value;
          var installments;
          var screen = 1;
          $('.form-group.second').hide();

          $('input[name=value]').mask('###0,00', {reverse: true});

          $('.sale-btn-next').click(function(event){
            event.preventDefault(); 

            value = $('input[name=value]').val();
            cpf = $('input[name=cpf]').val();
            installments = $('select[name=installments]').val();

            var userData = {
              "table": "users",
              "filter": 'cpf|'+cpf,
              "id": '',
              "method": "getRecord"
            };
            userData = JSON.stringify(userData);

            $.ajax({
                  url: 'webservice.php',
                  method: 'POST',
                  datatype: 'json',
                  data: {data: userData},
                  success: function(record){
                    if(record == ''){
                      notification('Cpf inválido', 'error');
                    }
                    else{
                      record = JSON.parse(record);
                      $('.form-group.first').fadeOut('fast', function() {

                        if(installments > 1){
                          $('span.value').html(value + ' (' + installments + 'x de ' + (parseFloat(value) / parseInt(installments)).toFixed(2) + ')');
                        }
                        else{
                          $('span.value').html(value);
                        }
                        $('span.name').html(record.name);
                        $('span.cpf').html(record.cpf);
                        
                        $('.form-group.second').fadeIn('fast');
                        screen = 2;
                      });

                    }
                    
                  }
            });
          });

          $('#saleForm div input').keyup(function(event) {
            if(event.keyCode == 13){
              event.preventDefault();
              if(screen == 1){
                $('.sale-btn-next').click();
              }
              else{
                $('.sale-btn-submit').click();
              }
            }
          });

          $('.sale-btn-back').click(function(event){
            event.preventDefault()
            $('.form-group.second').fadeOut('fast', function() {
              $('span.name').html('');
              $('span.cpf').html('');
              $('span.value').html('');
              $('input[name=password]').val('');
              $('.form-group.first').fadeIn('fast');
              screen = 1;
            });
          });

          $('.sale-btn-submit').click(function(event){
            event.preventDefault();
            var form = $('#saleForm').serializeArray();
            var data = {};
            for(i in form){
              data[form[i].name] = form[i].value;
            }
            data.method = 'makeSale';

            data = JSON.stringify(data);
            var l = new loading();
            $.ajax({
              url: 'webservice.php',
              method: 'POST',
              datatype: 'json',
              data: {data: data},
              beforeSend: function(){
                l.show();
              },
              success: function(response){
                console.log(response);
                if(response == 'session_error'){
                  location.reload();
                }
                response = JSON.parse(response);
                if(response.error == 'bad_password'){
                  notification('Senha incorreta', 'error');
                }
                if(response.error == 'balance_not_found'){
                  notification('Não há saldo cadastrado para o mês corrente', 'error');
                }
                if(response.error == 'balance_not_enough'){
                  customAlert({
                    text: "Saldo insuficiente<br>Saldo: R$ " + response.balance
                  });
                }
                if(response.error == '' && response[1] == null){
                  customAlert({ 
                    text: response.message,
                    func: function(){
                      location.reload();
                    }
                  });
                  l.hide();
                }
                l.hide();
                $('input[name=password]').val('');
              }
            })

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

        