
  // notification(usertype);
  var home = '';
  
  switch (usertype) {
    case 'dev':
      home = 'home'
      break;
    case 'estabelecimento':
      home = 'vendas/newVenda'
      break;
    default:
      // statements_def
      break;
  }

  var loadCustomMod = function(modules){
    $.get('modules/'+modules+'/index.php', function(html){
      $('#content-wrapper').fadeOut('fast', function(){
        $('#content-wrapper').html('').html(html);

        if(typeof afterLoad == 'function'){
          afterLoad();
        }

        $('#content-wrapper').fadeIn('fast');
      });
    });
    
  }

  $.get('modules/'+home+'/index.php', function(data){
    $('#content-wrapper').html(data);
  });

  var loadModule = function(modules){

    $.get('modules/'+modules+'/index.php', function(html){
        $('#content-wrapper').fadeOut('fast', function(){
          $('#content-wrapper').html('').html(html);
          
          /* Table Stuff */
          var fields = [];
          var table;
          var join;
          $('#dataTable').each(function(){
            table = this.dataset.table;
            filter = this.dataset.filters;
            join = this.dataset.join;
            $('thead tr th').each(function(){
              if(this.dataset.field != undefined){
                fields.push(this.dataset.field);
              }
            });
            
            var data = {
            "table": table,
            "fields": fields,
            "filter": filter,
            "join": join,
            "method": "getTableData"
            };

            data = JSON.stringify(data);            

            $.ajax({
              url: 'webservice.php',
              method: 'POST',
              datatype: 'json',
              data: {data: data}
            }).done(function(e){
              if(e == 'session_error'){
                location.reload();
                // $('#sessionModal').modal('show');
                return false;
              }
              var html;
              var tableData = JSON.parse(e);
              var tdata = [];
              for(i in tableData){
                if(tableData[i] != false){
                  tableData[i] = JSON.parse(tableData[i]);

                  html = '<tr id="'+tableData[i]['id']+'">';
                  for(j in tableData[i]){
                    // console.log(tableData[i][j]);
                    for(l in tableData[i][j]){
                      tdata[l] = tableData[i][j][l];
                    }
                  }
                   
                  for(l in fields){
                    // console.log(fields[l].replace(/-/g, "_").replace(".","__"));
                    html += '<td><span name="'+fields[l]+'">'+tdata[fields[l].replace(/-/g, "_").replace(".","__")]+'</span></td>';
                  }
                  html += '</tr>';
                  $('tbody').append(html);
                  
                }
              }
              
              $('#dataTable tbody tr').dblclick(function(){

                var data = {
                  "table": table,
                  "filter": filter,
                  "id": this.id,
                  "method": "getRecord"
                };
                data = JSON.stringify(data);

                $.ajax({
                  url: 'webservice.php',
                  method: 'POST',
                  datatype: 'json',
                  data: {data: data},
                  success: function(record){
                    if(record == 'session_error'){
                      location.reload();
                    }
                    // console.log(record);
                    record = JSON.parse(record);
                    for(i in record){
                      var value = record[i]
                      i = i.replace(/_/g, "-");
                      $('input[name='+i+']').val(value);
                      
                      if($('select[name='+i+']').val() != undefined){
                        value = (value == null) ? 0 : value;
                        $('select[name='+i+']').children('option[value=' + value + ']').attr('selected', true);
                      }
                    }
                    $('.table-form-delete').show();
                    $('.tab-container').toggle();
                    $('.menu-btn-new, .menu-btn-back, .menu-btn').toggle();
                  }
                });
                
              });

              var datatable = $('#dataTable').DataTable({
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

              $('.form-control.join').each(function(index, data){
                var select = $(this);
                var joinData = {
                  "table": data.dataset.join,
                  "fields": [data.dataset.field],
                  "filter": '',
                  "method": 'getTableData'
                }
                joinData = JSON.stringify(joinData);

                $.ajax({
                  url: 'webservice.php',
                  method: 'POST',
                  datatype: 'json',
                  data: {data: joinData},
                  success: function(response){
                    response = JSON.parse(response);
                    select.append('<option></option>');
                    for(i in response){
                      responseData = JSON.parse(response[i]);
                      // console.log(data.dataset.join);
                      select.append('<option value="' + responseData['id'] + '">' + responseData[data.dataset.join.replace(/-/g, '_')][data.dataset.field] + '</option>');
                      
                    }
                  }
                });

              });

              
              if(typeof afterLoad == 'function'){
                afterLoad(datatable);
              }
              $('#content-wrapper').fadeIn('fast');
            });

            $('.menu-btn-new, .menu-btn-back').click(function(){
              $('#dataForm').trigger('reset');
              $('input[name=id]').val('');
              $('.table-form-delete').hide();
              $('.menu-btn, .menu-btn-new, .menu-btn-back, .tab-container').toggle();
              
            });


            /*
              Exclusão De registros

              Registros serão excluidos logicamente (setando o campo deleted).
            */
            $('.table-form-delete').click(function(){
              event.preventDefault();
              // Executa a função preSubmit do script (se existir)
              if(typeof preDelete == 'function'){
                preDelete();
              }

              customConfirm({
                text: 'Deseja realmente excluir este registro?',
                yesFunction: function(){
                  var id = $('#dataForm input[name=id]').val();

                  data = {
                    'table': table,
                    'id': id,
                    'method': 'deleteData'
                  };
                  data = JSON.stringify(data);
                  $.ajax({
                    url: 'webservice.php',
                    method: 'POST',
                    datatype: 'json',
                    data: {data: data},
                    success: function(response){
                      response = JSON.parse(response);
                      if(response[1] == null){
                        notification('Registro excluido.');
                        return loadModule(modules);
                      }
                      else if(response[1] == 1451){ // Foreign key error
                        notification('Não foi possível excluir o registro.<br>Ele está vinculado a outro e isto causaria inconsistência no sistema.');
                      } 
                      else{
                        console.log(response);
                        notification('Falha ao excluir registro: '+response[2]);
                      }
                    },
                    error: function(e){
                      console.log(e);
                      notification('Falha ao excluir registro, Por favor informe o administrador do sistema');
                    }
                  });
                }
              });

            })

            /*
              Salvando Registro.

              Insert e Update são feitos na mesma função e tratados no server.
            */
            $('#dataForm').submit(function(event){
              event.preventDefault();
              
              if(!validate()){
                return false;
              }

              // Executa a função preSubmit do script (se existir)
              if(typeof preSubmit == 'function'){
                console.log('preSubmit fired');
                preSubmit();
              }


              var form = $('#dataForm').serializeArray();
              var formData = {};
              for(i in form){
                formData[form[i].name] = form[i].value;
              }
              // console.log(formData);
              data = {
                "table": table,
                "filter": filter,
                "data": formData,
                "method": "saveData"
              };
              
              data = JSON.stringify(data);

              $.ajax({
                url: 'webservice.php',
                method: 'POST',
                datatype: 'json',
                data: {data: data},
                success: function(response){
                  response = JSON.parse(response);
                  // console.log(response);
                  if(response[1] == 1062){
                    var dfield = $('label[for=' + response[2].split("'")[3].split('_')[0] + ']').text();
                    notification('Valor duplicado: '+dfield, 'error');
                  }
                  else{
                    notification('Registro salvo com sucesso.');
                    return loadModule(modules);
                  }
                },
                error: function(e){
                  console.log(e);
                  notification('Falha ao salvar registro.');
                }
              });

            });

          });
          


          

        });
        
        
    });
    return false;
  
  }

  $('.menu-item').click(function(){

    $('.main-menu .nav-item .dropdown-menu .dropdown-item').removeClass('selected');
    $(this).addClass('selected');
    
    var modules = this.dataset.module;
    var modtype = this.dataset.modtype;
    preSubmit = undefined;
    afterLoad = undefined;

    if(modtype == 'custom'){
      loadCustomMod(modules);
      return false;
    }
    else{
      return loadModule(modules);
    }

  });

  $('.btn-change-password-main').off('click').click(function(){

    var oldPassword = $('input[name=oldPassword]').val();
    var newPassword = $('input[name=newPassword]').val();
    var confirmPassword = $('input[name=confirmPassword]').val();

    if(newPassword == confirmPassword){
      var data = {
        "table": "users",
        "id": $('input[name=id]').val(),
        "data": {
          "oldPassword": oldPassword,
          "password": newPassword
        },
        "method": "changePassword"
      };

      data = JSON.stringify(data);

      $.post('webservice.php', {data: data}, function(e){
        response = JSON.parse(e);
        if(response.error){
          notification(response.message, 'error');
        }
        else if(response[1] == null){
          $('#changePasswordModal').modal('hide');
          notification('Senha alterada.');
        }
        else{
          console.log(e);
          notiification('Erro ao alterar a senha<br>Contate o administrador do sistema', 'error');
        }
      })
    }
    else{
      notification('Senha e confirmação não coincidem.', 'error');
    }

  });

  $('#changePasswordModal').on('hidden.bs.modal', function () {
    $('input[name=oldPassword]').val('');
    $('input[name=newPassword]').val('');
    $('input[name=confirmPassword]').val('');
  });

    // Add slideDown animation to Bootstrap dropdown when expanding.
  $('.dropdown').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
  });

  // Add slideUp animation to Bootstrap dropdown when collapsing.
  $('.dropdown').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
  });