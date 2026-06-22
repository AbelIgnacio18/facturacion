
<div class="col-12 mt-1">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">LISTA DE AGREMIADOS</h3>
        </div>
        <div class="card-body">
            <form id="frmResumen" name="frmResumen" submit="return false">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xl-4 col-sm-12">
                            <select name="status" id="status" class="form-control" onchange="listar_cliente()">
                                <option value="INHABILITADO">INHABILITADO</option>
                                <option value="HABILITADO">HABILITADO</option>
                                <option value="EXTRANJERO">EXTRANJERO</option>
                            </select>                            
                        </div>
                        <div class="col-lg-7 col-sm-12">
                            <div class="input-group">
                                <input type="text" class="form-control" name="filtro_agremiado" id="filtro_agremiado" placeholder="Escriba el filtro; cualquier dato de la tabla" >
                                
                            </div>

                        </div>
                    <input type="hidden" name="accion" id="accion" value="ENVIO_BAJAS">
                    <input type="hidden" name="ids" id="ids" value="0">
                    
                    

                    
                    
                    
                    

                  
                </div>
                <div class="col-md-12 mt-3" id="altura_tabla"  style="overflow: scroll;height:450px;">
                    <table class="table table-hover table-sm" id="geeks"  >
                        <thead>
                            <tr>
                            
                                <th>ID</th>
                                <th>CODIGO</th>
                                <th>NOMBRE COMPLETO</th>
                                <th>DNI</th>
                                <th>PAGO</th>
                                <th>VIGENCIA</th>
                      
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="listadoclientes">
                            
                        </tbody>
                        
                    </table>
                </div>
                
            </form>
            
            
            
            
                <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editmodal">Actualizar    <input type="text" class="form-control" name="razon_social" id="iditem" disabled=""></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                   <div class="modal-body">
                      <div class="container-fluid">
                     <div class="row">
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>DOC. IDENT.</label>
                            <select name="tipodoc" id="tipodoc" class="form-control">
                                <option value="1" selected>DNI</option>
                            </select>
                                </div>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>NRO DOC.</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nrodoc" id="nrodoc" >
                                <div class="input-group-addon">
                                    <button class="btn btn-default" type="button" onclick="ObtenerDatosDNI()">
                                        <li class="fas fa-search" title="Buscar"></li>
                                    </button>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>NOMBRE COMPLETO</label>
                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="NOMBRE COMPLETO">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>DIRECCION</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" placeholder="DIRECCION">
                                </div>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>EMAIL</label>
                            <input type="email" class="form-control" name="correo" id="correo" placeholder="EMAIL">
                                </div>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                              <div class="p-1">
                            <label>TELEFONO</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" placeholder="telefono">
                                </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group ">
                             <div class="p-1">
                                   <label>NUMERO DE COLEGIATURA</label>
                            <input type="text" class="form-control" name="colegiatura" id="codigo" placeholder=" NUMERO DE REGISTRO">
                            </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                                <div class="p-1">
                            <label>FECHA PAGO</label>
                            <input type="date" class="form-control" name="fecha_pago" id="fecha_pago" value="<?php echo date('Y-m-d')?>">
                            </div>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                                <div class="p-1">
                            <label>FECHA DE COLEGIATURA</label>
                            <input type="date" class="form-control " name="fechacolegiatura" id="fechacolegiatura" value="<?php echo date('Y-m-d')?>">
                              </div>
                        </div>
                        </div>
                    </div>
                       
                   
                      </div>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger"  data-dismiss="modal" >CERRAR</button>
                    <button type="submit" class="btn btn-primary" onclick="actualizar()">ACTUALIZAR</button>
                  </div>
                </div>
              </div>
            </div>
            
            
            
        </div>        
    </div>
</div>   

<script>
            $(document).ready(function() {
                $("#filtro_agremiado").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#geeks tr").filter(function() {
                        $(this).toggle($(this).text()
                        .toLowerCase().indexOf(value) > -1)
                    }); 
                });
            });
</script>
<script>
listar_cliente();
//let altura_interfaz = window.innerHeight;
//altura_tabla= altura_interfaz - 300;
//$('#altura_tabla').css('height',altura_tabla);

  function listar_cliente(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_CLIENTE",
                "status" :$('#status').val()
            }
        }).done(function(data){
    
            json = JSON.parse(data);
            
            clientes = json.listado;
            options = '';
            
            
            
             for(i = 0; i < clientes.length; i++){
                
                options = options + '<tr><td>'+clientes[i].id+'</td><td>'+clientes[i].codigo+'</td><td>'+clientes[i].razon_social+'</td><td>'+clientes[i].nrodoc+'</td><td>'+clientes[i].fecha_pago+'</td><td>'+clientes[i].fecha_vigencia+'</td><td>'+clientes[i].Status+'</td><td><button type="button" class="btn btn-danger btn-xs mr-1" onclick="eliminar(' +clientes[i].id+ ');">Eliminar</button><button type="button" class="btn btn-primary btn-xs mr-1" onclick="editar(' +clientes[i].id+ ')"  data-toggle="modal" data-target="#editmodal">editar</button></td></tr>';
            }

            $('#listadoclientes').html(options)
        })
    }
    
    
      function eliminar(id){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ELIMINAR_AGREMIADO",
                "id" : id
              
            }
        }).done(function(data){
           
             
              Swal.fire({
              icon: "success",
              title: "ELIMINADO...",
              text: "Registro eliminado",
          
            });
        })
        
        $.ajax({
        method:"GET",
        url:"view/productos.php"
        }).done(function(data){
        $('#contenido_principal').html(data)
         $.ajax({
                        method:"GET",
                        url:"view/agremiados.php"
                        }).done(function(data){
                        $('#contenido_principal').html(data)
                        } 
                    )
        
        
        
        
        
        } 
    )
        

    }
 
    
      function editar(id){
          
          $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "EDITAR_ID_AGREMIADO",
                "id" : id
              
            }
        }).done(function(data){
             
            json = JSON.parse(data);
            cliente = json.listado;
            
            console.log(cliente);
         $('#iditem').val(cliente[0].id),
            $('#codigo').val(cliente[0].codigo),
            $('#razon_social').val(cliente[0].razon_social),
            $('#tipodoc').val(cliente[0].tipodoc),
            $('#nrodoc').val(cliente[0].nrodoc),
            $('#fecha_pago').val(cliente[0].fecha_pago),
            $('#fechacolegiatura').val(cliente[0].fechacolegiatura),
            $('#direccion').val(cliente[0].direccion),
            $('#correo').val(cliente[0].correo_electronico),
            $('#telefono').val(cliente[0].telefono)
      })
        
          
    }
    
    function actualizar(){
       
            $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ACTUALIZAR_AGREMIADOFF",
                
              "id" : $('#iditem').val(),
                "codigo" : $('#codigo').val(),
                "razon_social" : $('#razon_social').val(),
                "tipodoc" : $('#tipodoc').val(),
                "nrodoc" : $('#nrodoc').val(),
                "fecha_pago" : $('#fecha_pago').val(),
                "fechacolegiatura" : $('#fechacolegiatura').val(),
                "direccion" : $('#direccion').val(),
                "correo" : $('#correo').val(),
                "telefono" : $('#telefono').val()
              
            }
        
        }).done(function(text){
      
      
             
                Swal.fire({
              icon: "success",
              title: "ACTUALIZADO...",
              text: "Registro Actualizado !",
          
            });
      
      
         $('#editmodal').modal('hide');
         
        
        })
        
    } 
    
    
    
    
    
</script>
