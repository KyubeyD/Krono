$(document).ready(function(){
    $('#cnpj').mask('00.000.000/0000-00');
});

$(document).ready(function(){
    $('#cpf').mask('000.000.000-00');
});

$(document).ready(function(){
    $('#telefone').mask('(00) 00000-0000');
});

$(document).ready(function(){
    $('#telefone1').mask('(00) 00000-0000');
});

$(document).ready(function(){
    $('#telefone2').mask('(00) 00000-0000');
});

$(document).ready(function(){
    $('#cep').mask('00000-000');
});

$(document).ready(function(){
    $('#horario_inicial').mask('00:00', {
        onKeyPress: function(val, e, field, options) {
            // Separando horas e minutos
            let horas = val.split(':')[0];
            let minutos = val.split(':')[1];

            // Impedir que as horas sejam maiores que 23
            if (horas.length === 2 && parseInt(horas) > 23) {
                horas = '23';
            }

            // Impedir que os minutos sejam maiores que 59
            if (minutos && parseInt(minutos) > 59) {
                minutos = '59';
            }

            // Atualizar o valor do campo com as horas e minutos válidos
            field.val(horas + (minutos ? ':' + minutos : ''));
        }
    });

    // Impedir valores inválidos ao perder o foco
    $('#horario_inicial').on('blur', function() {
        let val = $(this).val();
        let horas = val.split(':')[0];
        let minutos = val.split(':')[1];

        if (parseInt(horas) > 23) horas = '23';
        if (parseInt(minutos) > 59) minutos = '59';

        $(this).val(horas + ':' + (minutos ? minutos : '00'));
    });
});