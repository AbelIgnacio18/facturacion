
<div class="col-12 mt-1">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">LISTA DE CAJAS</h3>
        </div>
        <div class="card-body">
            <form id="frmResumen" name="frmResumen" submit="return false">
                <div class="col-md-12 responsive-table">
                
                    <input type="hidden" name="accion" id="accion" value="ENVIO_BAJAS">
                    <input type="hidden" name="ids" id="ids" value="0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                              <th>ID</th>
                         
                                <th>USUARIO</th>
                                <th>FECHA APERTURA</th>
                                <th>FECHA CIERRE</th>
                                <th>FONDO INICIAL</th>
                                <th>EFECTIVO</th>
                                <th>TRANSFE. DEPS.</th>
                                <th>GASTO.</th>
                                <th>FONDO CIERRE</th>
                                 <th>ESTADO</th>
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="listarcaja">
                             
                        </tbody>
                        
                    </table>

                 
                  
                </div>
            </form>
            
  
                        
            
            
            
            
            
            
            
            
          


        </div>        
    </div>
</div>


<script>
listar_caja();
console.log($("#users").val());
   
    function listar_caja(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_CAJA",
                 "user" : $("#users").val(),
            }
        }).done(function(data){

            json = JSON.parse(data);
            console.log(json);
            caja = json.listado;
            options = '';
            
            //console.log(caja);
            const fechaActual = new Date();
            const año = fechaActual.getFullYear();
            const mes = String(fechaActual.getMonth()+1).padStart(2,'0');
            const dia = String(fechaActual.getDate()).padStart(2,'0');
            const  fechaString= `${año}-${mes}-${dia}`;
            console.log(fechaString);
             for(i = 0; i < caja.length; i++){
                 estilo_fila = "";
                 if(caja[i].fecha_apertura==fechaString){
                     estilo_fila = "background-color:#ececec;"
                 }
                 
                 
                 
                    cierre_caja = (parseFloat(caja[i].inicial)+parseFloat(caja[i].efectivo) + parseFloat(caja[i].transferencia) - parseFloat(caja[i].gasto)).toFixed(2);
                    
                        
                    if((caja[i].efectivo == null)){
                        caja[i].efectivo = "";
                    }
                    if((caja[i].transferencia == null)){
                        caja[i].transferencia = "";
                    }
                    if((cierre_caja == "NaN")){
                        cierre_caja = "";
                    }
                    if((caja[i].fechacierre == null)){
                        caja[i].fechacierre = "";
                    }
                    if((caja[i].gasto == null)){
                        caja[i].gasto = "";
                    }
                    
                    
                options = options + ' <tr style='+estilo_fila+' ><td>'+caja[i].id+'</td><td>'+caja[i].referencia+'</td><td>'+caja[i].fechaapertura+'</td><td>'+caja[i].fechacierre+'</td><td>'+caja[i].inicial+'</td><td>'+caja[i].efectivo+'</td><td>'+caja[i].transferencia+'</td><td>'+caja[i].gasto+'</td><td>'+cierre_caja+'</td><td>'+caja[i].estado+'</td><td><button type="button" id="'+caja[i].id+'" class="btn-report btn btn-primary btn-xs mr-1" >REPORTE</button></td></tr>';
            }

            $('#listarcaja').html(options)
        })
    }
  
    $(document).on('click', '.btn-report', function() {
        var id = $(this).attr('id');
        $.ajax({
            url: 'view/cajapdf.php',
            method: 'GET',
            data: { id: id},
            success: function(result) {
                window.open('view/cajapdf.php?id=' + id, '_blank');
            },
        });
    });

    
    
</script>

