$(document).ready(() => {
	
    $("#documentacao").on('click', function(){
        // $('#pagina').load("documentacao.html");
        
        // $.get('documentacao.html', function(data){
        //     $("#pagina").html(data);
        // });

        $.post('documentacao.html', function(data){
            $("#pagina").html(data);
        });
    
    }); 

    $("#suporte").on('click', function(){
        // $("#pagina").load("suporte.html");

        $.post('suporte.html', function(data){
            $("#pagina").html(data);
        });
    }); 

    //ajax
    $("#competencia").on('change', function(){
        let valor  = $(this).val();

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: 'competencia='+valor,
            dataType: 'json',
            encode: true,
            success: function(data){
                $("#numeroVendas").text(data.numeroVendas);
                $("#totalVendas").text(data.totalVendas);
                $("#clientesAtivos").text(data.clientesAtivos);
                $("#clientesInativos").text(data.clientesInativos);
                $("#totalDespesas").text(data.totalDespesas);
                $("#reclamacoes").text(data.criticas);
                $("#sugestoes").text(data.sugestoes);
                $("#elogios").text(data.elogios);
            },
            error: function(textStatus){
                console.log(textStatus);
            }
        })

    })

})