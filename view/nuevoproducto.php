
<div class="col-12 mt-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">NUEVO PRODUCTO</h3>
        </div>
        <div class="card-body">
            <form id="frmVenta" submit= "return false">
                <input type="hidden" name="accion" id="accion" value="GUARDAR_VENTA">
                <div class="row">
                    
                        
                 
                        
                        
                        <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>NOMBRE COMPLETO</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="NOMBRE COMPLETO">
                                </div>
                        </div>
                        
                           <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>PRECIO</label>
                            <input type="number" class="form-control" name="precio" id="precio" placeholder="">
                                </div>
                        </div>
                        
                         <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>tipo PRECIO</label>
                            <select class="form-control mb-3" id="tipoprecio">
                          <option selected>Selecione</option>
                          <option value="01">01</option>
                        </select>
                        
                            </div>
                        </div>
                        
                        
                           <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>CODIGO AFECTACION</label>
                         <select class="form-control mb-3" id="codigoafectacion">
                          <option selected>Seleccione</option>
                          <option value="10">Gravado - Operación Onerosa</option>
                          <option value="20"> Exonerado - Operación Onerosa</option>
                          <option value="30">Inafecto - Operación Onerosa</option>
                        </select>
                        </div>
                        </div>
                        
                            <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>UNIDAD</label>
                           <select class="form-control mb-3" id="unidad">
                          <option selected>Seleccione</option>
                          <option value="NIU">NIU-unidad de producto</option>
                          <option value="ZZ">ZZ-unidad servicio</option>
                       
                        </select>
                                
                                </div>
                             </div>
                             
                            <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>LOTE(FOLIO)</label>
                            <input type="text" class="form-control" name="razon_social" id="lote" placeholder="LOTE">
                                </div>
                        </div>
                            <div class="form-group col-md-3">
                              <div class="p-1">
                            <label>STOCK</label>
                            <input type="number" class="form-control" name="razon_social" id="stock" placeholder="STOCK">
                                </div>
                        </div>
                   

                </div>

            

                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="addproducto4()"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" onclick="Cancelar()"><i class="fa fa-trash-alt"></i> Cancelar</button>
                </div>
            </form>
        </div>        
    </div>
</div>   


<script>
    
    
    function addproducto4(){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ADD_PRODUCTO4WW",
             
                "nombre" : $('#nombre').val(),
                "precio" : $('#precio').val(),
                "tipo_precio" : $('#tipoprecio').val(),
                "codigo_afectacion" : $('#codigoafectacion').val(),
                "unidad" : $('#unidad').val(),
                "lote" : $('#lote').val(),
                "stock" : $('#stock').val(),
                
            }
        }).done(function(data){
           Swal.fire({
              icon: "success",
              title: "GUARDADO...",
              text: "Se a guardado un registro!",
          
            });
        })
        
        
        //Una vez guardado enviar a lista de productos
        $.ajax({
        method:"GET",
        url:"view/productos.php"
        }).done(function(data){
        $('#contenido_principal').html(data)
        } 
    )
    
    
    

    }
function cancelar(){
     

    }
</script>
