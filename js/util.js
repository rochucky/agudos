var notyAnimation = {
    open: function (promise) {
        var n = this;
        var Timeline = new mojs.Timeline();
        var body = new mojs.Html({
            el        : n.barDom,
            x         : {500: 0, delay: 0, duration: 500, easing: 'elastic.out'},
            isForce3d : true,
            onComplete: function () {
                promise(function(resolve) {
                    resolve();
                })
            }
        });

        var parent = new mojs.Shape({
            parent: n.barDom,
            width      : 200,
            height     : n.barDom.getBoundingClientRect().height,
            radius     : 0,
            x          : {[150]: -150},
            duration   : 1.2 * 500,
            isShowStart: true
        });

        n.barDom.style['overflow'] = 'visible';
        parent.el.style['overflow'] = 'hidden';

        var burst = new mojs.Burst({
            parent  : parent.el,
            count   : 10,
            top     : n.barDom.getBoundingClientRect().height + 75,
            degree  : 90,
            radius  : 75,
            angle   : {[-90]: 40},
            children: {
                fill     : '#EBD761',
                delay    : 'stagger(500, -50)',
                radius   : 'rand(8, 25)',
                direction: -1,
                isSwirl  : true
            }
        });

        var fadeBurst = new mojs.Burst({
            parent  : parent.el,
            count   : 2,
            degree  : 0,
            angle   : 75,
            radius  : {0: 100},
            top     : '90%',
            children: {
                fill     : '#EBD761',
                pathScale: [.65, 1],
                radius   : 'rand(12, 15)',
                direction: [-1, 1],
                delay    : .8 * 500,
                isSwirl  : true
            }
        });

        Timeline.add(body, burst, fadeBurst, parent);
        Timeline.play();
    },
    close: function (promise) {
        var n = this;
        new mojs.Html({
            el        : n.barDom,
            x         : {0: 500, delay: 10, duration: 500, easing: 'cubic.out'},
            skewY     : {0: 10, delay: 10, duration: 500, easing: 'cubic.out'},
            isForce3d : true,
            onComplete: function () {
                promise(function(resolve) {
                    resolve();
                })
            }
        }).play();
    }
}

var notification = function(text, type){

	var type = type || 'info';

	new Noty({
		theme: 'nest',
		text: text,
		type: type,
		animation: notyAnimation,
		timeout: 3000
	}).show();
}

var customAlert = function(param){

	var text = param.text;
    var func = param.func;

	var alertModal = new Noty({
		theme: 'relax',
		text: '<p class="text-center">' + text + '</p>',
		type: 'alert',
		layout: 'center',
		modal: true,
		animation: notyAnimation,
        closeWith: ['button'],
		buttons: [
		    Noty.button('OK', 'btn btn-primary btn-block', function () {
		        alertModal.close();
                if(func != undefined){
                    func();
                }
		    }, {id: 'alert-ok-btn', 'data-status': 'ok'})
    	],
        callbacks: {
            afterShow: function(){ // Estava zoando os botões
                $('div[data-name=mojs-shape]').hide();
            }
        }
	}).show();

	$('#alert-ok-btn').focus();
}

var customConfirm = function(param){

	var alertModal = new Noty({
		theme: 'relax',
		text: '<p class="text-center">' + param.text + '</p>',
		type: 'alert',
		layout: 'center',
		modal: true,
		animation: notyAnimation,
        closeWith: [''],
		buttons: [
		    Noty.button('Sim', 'btn btn-success', function(){
		    	param.yesFunction();
		    	alertModal.close();
		    }, {id: 'alert-yes-btn', 'data-status': 'yes'}),
		    Noty.button('Não', 'btn btn-basic', function () {
		        alertModal.close();
		    }, {id: 'alert-no-btn', 'data-status': 'no'})
    	],
        callbacks: {
            afterShow: function(){ // Estava zoando os botões
                $('div[data-name=mojs-shape]').hide();
            }
        }
	}).show();

	$('#alert-yes-btn').focus();
}

function validarCPF(cpf) {  
    cpf = cpf.replace(/[^\d]+/g,'');    
    if(cpf == '') return false; 
    // Elimina CPFs invalidos conhecidos    
    if (cpf.length != 11 || 
        cpf == "00000000000" || 
        cpf == "11111111111" || 
        cpf == "22222222222" || 
        cpf == "33333333333" || 
        cpf == "44444444444" || 
        cpf == "55555555555" || 
        cpf == "66666666666" || 
        cpf == "77777777777" || 
        cpf == "88888888888" || 
        cpf == "99999999999")
            return false;       
    // Valida 1o digito 
    add = 0;    
    for (i=0; i < 9; i ++)      
        add += parseInt(cpf.charAt(i)) * (10 - i);  
        rev = 11 - (add % 11);  
        if (rev == 10 || rev == 11)     
            rev = 0;    
        if (rev != parseInt(cpf.charAt(9)))     
            return false;       
    // Valida 2o digito 
    add = 0;    
    for (i = 0; i < 10; i ++)       
        add += parseInt(cpf.charAt(i)) * (11 - i);  
    rev = 11 - (add % 11);  
    if (rev == 10 || rev == 11) 
        rev = 0;    
    if (rev != parseInt(cpf.charAt(10)))
        return false;       
    return true;   
}

function validarCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
}

function validarEmail(field) {
    usuario = field.substring(0, field.indexOf("@"));
    dominio = field.substring(field.indexOf("@")+ 1, field.length);
     
    if ((usuario.length >=1) &&
        (dominio.length >=3) && 
        (usuario.search("@")==-1) && 
        (dominio.search("@")==-1) &&
        (usuario.search(" ")==-1) && 
        (dominio.search(" ")==-1) &&
        (dominio.search(".")!=-1) &&      
        (dominio.indexOf(".") >=1)&& 
        (dominio.lastIndexOf(".") < dominio.length - 1)) 
    {
        return true;
    }
    else{
        return false;
    }
}

var addButton = function(name, func){
    var className = name.replace(/ /g,'-');
    var html = '<button type="button" class="btn btn-primary menu-btn custom-btn-'+className+' hidden" data-content="table-responsive">'+name+'</button>';
    html += $('.mod-buttons').html();
    $('.mod-buttons').html('').append(html);
    
    $('.custom-btn-'+className).click(func);
}

var validate = function(){
    var response = true;
    $('form div [required]').each(function(index, data){
        $(this).removeClass('invalid');
        if(data.value == ''){
            console.log($(this).attr('name'));
            $(this).addClass('invalid');
            notification("Campo obrigatório: " + $('label[for=' + $(this).attr('name') + ']').text(), 'error');
            response = false;
        }
    });


    if($('input[name=cpf]').val() != undefined){
        $('input[name=cpf]').removeClass('invalid');
        if(!validarCPF($('input[name=cpf]').val())){
          notification('CPF inválido', 'error');
          $('input[name=cpf]').addClass('invalid');
          response = false;
        }
    }

    if($('input[name=cnpj]').val() != undefined){
        $('input[name=cnpj]').removeClass('invalid');
        if(!validarCNPJ($('input[name=cnpj]').val())){
          notification('CNPJ inválido', 'error');
          $('input[name=cnpj]').addClass('invalid');
          response = false;
        }
    }

    $('input[type=email]').each(function(index, data){
        if($(this).val() != undefined){
            $(this).removeClass('invalid');
            if(!validarEmail($(this).val())){
              notification('Email inválido', 'error');
              $(this).addClass('invalid');
              response = false;
            }
        }
        
    });


    

    return response;
}


class loading{
    construct(){
        
    }

    show(){
        this.noty = new Noty({
            theme: 'relax',
            text: '<p class="text-center"><img src="images/loading.gif"><br>Carregando</p>',
            type: 'alert',
            layout: 'center',
            modal: true,
            closeWith: ''
        });

        this.noty.show();
    }
    hide(){
        this.noty.close();
    }
}