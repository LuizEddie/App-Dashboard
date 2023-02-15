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


})