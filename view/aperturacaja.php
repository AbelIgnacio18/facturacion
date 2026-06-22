    
<div class="col-12 mt-1">
    <div id="cabezera3" class="card card-primary">
        <div class="card-header">
            <h3 class="card-title" id="titulo_apertura">APERTURA DE CAJA</h3>
            <h3 class="d-none card-title" id="titulo_cierre">CIERRE DE CAJA</h3>
        </div>
            <div class="row">
                       
                     <div class="col-md-4 " >
                                                       <div class="form-group col-md-12">
                                                              <div class="p-1">
                                                            <label>Monto Inicial</label>
                                                            <input type="number" class="form-control" name="razon_social" id="fondoinicial" placeholder="0.00 " value="0" required>
                                                                </div>
                                                        </div>
                    </div>
                    <div class="col-md-4 " >
                       
                                                        
                                                        
                            <div class="form-group ">
                                      <div class="p-1">
                                    <label>Usuario</label>
                                    <label>SERIE</label>
                            <select name="usuario" id="usuario" class="form-control" ></select>
                                    </div>
                                </div>
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                    </div>
                    
                    
                    <div class="col-md-4 " >
                        <div class="form-group col-md-12">
                                                        <div class="p-1">
                                                    <label>Fecha de Apertura</label>
                                                    <input  type="datetime-local" class="form-control" name="fechaapertura" id="fechaapertura" value="<?php date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');echo date("Y-m-d\TH:i:s");?>">
                                                    </div>
                                                </div>
                    </div>
                    
                </div>
                <div class="row justify-content-end card-footer" id="content-button">
                    <div class=" text-right ">
                                            <button id="btn_apertura" type="button" class="btn btn-primary" onclick="aperturacaja()"><i class="fa fa-save"></i> APERTURAR</button>
                    </div>
                </div>
                <div id="cajacierre" class="d-none">
                <div class="row">
                    
                    
                            
                    
                    
                    
                    
                    
                     <div class="col-md-4 " >
                                                       <div class="form-group col-md-12">
                                                              <div class="p-1">
                                                            <label>Efectivo</label>
                                                            <input type="number" class="form-control" name="efectivo" id="efectivo" placeholder="0.00 " value="0" >
                                                                </div>
                                                        </div>
                    </div>
                    <div class="col-md-4 " >
                        <div class="form-group col-md-12">
                                                              <div class="p-1">
                                                            <label>Transferencia</label>
                                                           <input type="number" class="form-control" name="transferencia" id="transferencia" placeholder="0.00 " value="0" >
                                                                </div>
                                                        </div>
                    </div>
                    <div class="col-md-4 " >
                        <div class="form-group col-md-12">
                                                        <div class="p-1">
                                                    <label>Gasto</label>
                                                           <input type="number" class="form-control" name="gasto" id="gasto" placeholder="0.00 " value="0">
                                                    </div>
                                                </div>
                    </div>
                    <div class="col-md-4 " >
                        <div class="form-group col-md-12">
                                                        <div class="p-1">
                                                    <label>Fecha de Cierre</label>
                                                    <input  type="datetime-local" class="form-control" name="fechacierre" id="fechacierre" value="<?php date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');;echo date("Y-m-d\TH:i:s");?>">
                                                    </div>
                                                </div>
                    </div>
                    
                </div>
                <div  class=" row justify-content-end card-footer">
                    <div class=" text-right ">
                                            <button type="button" class="btn btn-danger" onclick="cerrarcaja()"><i class="fa fa-save"></i> CERRAR</button>
                    </div>
                </div>
                </div>
    </div>
</div>


<script>
    
   listar_series();
   obteneraperturaandinicial()
   
   
   function listar_series(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_SERIES",
                "tipocomp": "03",
                "users": $("#users").val()
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option style="float:left" value="' + listado[i].serie + '">' + listado[i].serie + '</option>';                               
            }

            $('#usuario').html(options);
        })
    }
   
   
    function traertotalesefectivo(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "OBTENER_TEFECTIVO",
                 "user" : $("#users").val(),
               
            }
        }).done(function(data){
            $('#efectivo').val(data)
            
        })
    }
    
    function traertotalestransferencia(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "OBTENER_TTRANSFERENCIA",
                 "user" : $("#users").val(),
              
            }
        }).done(function(data){
           
            $('#transferencia').val(data)
        })
    }
    
     
    function obteneraperturaandinicial(){
        console.log( $("#users").val());
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "obteneraperturaandinicial",
                 "user" : $("#users").val(),
               
            }
        }).done(function(data){
            json = JSON.parse(data);
            listado = json.listado;
         console.log(json[0].fechaapertura);
         $('#fondoinicial').val(json[0].inicial);
          $('#fechaapertura').val(json[0].fechaapertura);
          if(json[0].efectivo == null ){
              efectivo = 0.00;
          }else{
              efectivo = json[0].efectivo;
          }
          if(json[0].transferencia == null){
              transferencia = 0.00;
          }else{
              transferencia = json[0].transferencia;
          }
          $('#efectivo').val(efectivo);
          $('#transferencia').val(transferencia);
         
        // {"fechaapertura":"2025-06-10 08:17:19","inicial":"10.00"}
      
         
        })
    }
    
    
    
    
    
    function aperturacaja(){
        
         usu=$('#usuario').val(),
         inicial=$('#fondoinicial').val(),
         fechaapertura=$('#fechaapertura').val()
         
      if(usu!=="" && inicial!==""){
           //codigo de BD guardar la apertura
              $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "apertura_cajas_nuevo",
                
                 "referencia" : $("#users").val(),
                 "usuario" :  $('#usuario').val(),
                "fondoinicial" : $('#fondoinicial').val(),
                "fechaapertura" : $('#fechaapertura').val(),
            }
        }).done(function(data){
         
          
    
        
         Swal.fire({
              icon: "success",
              title: "GUARDADO...",
              text: "Se aperturo caja!",
          
            });
          
          
            //if($('#users').val() == "RUTH"){
                //localStorage.setItem("usuario1", 1);
                //cambiar_estilo();
            //}
            //if($('#users').val() == "MARIA"){
                //localStorage.setItem("usuario2", 2);
                //cambiar_estilo();
            //}
            cambiar_estilo();
            $('#fondoinicial').attr('disabled','disabled');
            $('#usuario').attr('disabled','disabled');
            consultarAperturaCaja();
        }) 
          
      }else{
       
          Swal.fire({
              icon: "error",
              title: "faltan Datos...",
              text: "Ingreso los campos!",
          
            });
      }   
       
        
        
       
        }
        
     function cambiar_estilo(){
         $("#content-button").addClass("d-none");
          $("#cabezera3").removeClass("card-primary");
          $("#cabezera3").addClass("card-danger");
          $("#titulo_apertura").addClass("d-none");
          $("#titulo_cierre").removeClass("d-none");
          $("#cajacierre").removeClass("d-none");
     }
     
     function cerrarcaja(){
           
            $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "actualizar_cajas_cierre",
                
                "referencia" : $("#users").val(),
                "usuario" : $('#usuario').val(),
                "efectivo" : $('#efectivo').val(),
                "transferencia" : $('#transferencia').val(),
                "gasto" : $('#gasto').val(),
                "fechacierre" : $('#fechacierre').val()
            }
        }).done(function(data){
         
          
              
               
                Swal.fire({
              icon: "success",
              title: "ACTUALIZADO...",
              text: "Caja Cerrado !",
          
            });
               
               
            //if($('#users').val() == "RUTH"){
                //localStorage.removeItem("usuario1");
            //}
            //if($('#users').val() == "MARIA"){
                //localStorage.removeItem("usuario2");
            //}  
         consultarAperturaCaja();  
       
         ircaja();
          
        }) 
         
         
         
        
     }
</script>








