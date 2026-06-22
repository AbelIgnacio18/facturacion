
<div class="col-12 mt-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">NUEVO DE AGREMIADOS</h3>
        </div>
        <div class="card-body">
            <form id="frmVenta" submit= "return false">
                <input type="hidden" name="accion" id="accion" value="CLIENTE_AGREMIADOS">
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
                                   <label>NUMERO DE REGISTRO(CODIGO)</label>
                            <input type="text" class="form-control" name="codigo" id="codigo" placeholder=" NUMERO DE REGISTRO">
                            </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                                <div class="p-1">
                            <label>FECHA COLEGIATURA PAGO</label>
                            <input type="date" class="form-control" name="fecha_pago" id="fecha_pago" value="">
                            </div>
                        </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                                <div class="p-1">
                            <label>FECHA DE COLEGIATURA</label>
                            <input type="date" class="form-control " name="fechacolegiatura" id="fechacolegiatura" value="">
                              </div>
                        </div>
                        </div>
                    </div>
                

                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="Guardaragremiado()"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" onclick="Cancelar()"><i class="fa fa-trash-alt"></i> Cancelar</button>
                </div>
            </form>
        </div>        
    </div>
</div>   


<script>
    
    function ObtenerDatosDNI(){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "CONSULTA_DNI",
                "dni" : $('#nrodoc').val()
            }
        }).done(function(text){

            json = JSON.parse(text);
            
            $('#razon_social').val(json.nombres+' '+json.apellidoPaterno+' '+json.apellidoMaterno);
            $('#direccion').val('');
        })
    }
      
      
      
            
      
      
      
      
    function Guardaragremiado(){
  
          $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ADD_CLIENTE",
                
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
        }).done(function(data){
               
                 Swal.fire({
              icon: "success",
              title: "GUARDADO...",
              text: "Registro guardado",
          
            });
                
                 $.ajax({
                      method:"GET",
                      url:"view/agremiados.php"
                    }).done(function(data){
                      $('#contenido_principal').html(data)
                    })
                
        })
    }
    
 

</script>
