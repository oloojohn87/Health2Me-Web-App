$(document).ready(function() { 
  
  
    function CheckPrevio(tipo,selector){
	   var dato = $(selector).val();
	   var quevalor = dato;
	   var pasa = 1;
	     
	   var queTipo=1;
        if (dato != ''){
	     var cadena = 'CheckPrevioPac.php?valor='+dato+'&queTipo='+queTipo;
	     var RecTipo = LanzaAjax (cadena);
	    
             if (RecTipo>0){
                 var mensaje = 'Email already exists';
                 alarma (mensaje,selector);
                 quevalor = '';
                 pasa = 0;
                 $('#ValorGlobal').val('-1');
                  highlighterrorfield(selector);
             }
       
            
        }else {
          pasa=1;
         }

	     if (pasa==1) {alarma('',selector); $('#ValorGlobal').val('0'); }
	     $(selector).val(quevalor);
	 }
    
    
    	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
		$.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}    


});