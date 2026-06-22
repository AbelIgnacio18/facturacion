
<div class="col-12 mt-1">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">LISTA DE PRODUCTOS</h3>
        </div>
        <div class="card-body">
            <form id="frmResumen" name="frmResumen" submit="return false">
                <div class="col-md-12">
                
                    <input type="hidden" name="accion" id="accion" value="ENVIO_BAJAS">
                    <input type="hidden" name="ids" id="ids" value="0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                              
                          
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>PRECIO</th>
                                <th>TIPO PRECIO</th>
                                <th>CODIGO AFECTACION</th>
                                <th>UNIDAD</th>
                                <th>FOLIO(LOTE)</th>
                                <th>STOCK</th>
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="listadoproducto">
                        
                        </tbody>
                        
                    </table>

                 
                  
                </div>
            </form>
            
            
            <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editmodal">Actualizar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                   <div class="modal-body">
                      <div class="container-fluid">
                        <div class="row">
                            
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>NOMBRE COMPLETO</label>
                                    <input type="text" class="form-control" name="nombre3" id="nombreact" placeholder="NOMBRE COMPLETO">
                                        </div>
                                </div>
                                
                                 <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>PRECIO</label>
                                    <input type="number" class="form-control" name="precio" id="precioact" placeholder="">
                                        </div>
                                </div>
                                
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>tipo PRECIO</label>
                                    <select class="form-control mb-3" id="tipoprecioact">
                                  
                                      <option value="" selected id="tipoprecioactu"></option>
                                      <option value="01">01</option>
                                    </select>
                                
                                    </div>
                                </div>
                                
                                
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                        <label>CODIGO AFECTACION</label>
                                         <select class="form-control mb-3" id="codigoafectacionact">
                                       
                                        <option value="" selected id="codigoafectacionactu"></option>
                                          <option value="10">Gravado - Operación Onerosa</option>
                                          <option value="20"> Exonerado - Operación Onerosa</option>
                                          <option value="30">Inafecto - Operación Onerosa</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                            <label>UNIDAD</label>
                                           <select class="form-control mb-3" id="unidadact">
                                               <option value="" selected id="unidadactu"></option>
                                              <option value="NIU">NIU-unidad de producto</option>
                                              <option value="ZZ">ZZ-unidad servicio</option>
                                           
                                            </select>
                                                    
                                        </div>
                                </div>
                                     
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>LOTE(FOLIO)</label>
                                    <input type="text" class="form-control" name="razon_social" id="loteact" placeholder="LOTE">
                                        </div>
                                </div>
                                
                                
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>STOCK</label>
                                    <input type="number" class="form-control" name="razon_social" id="stockact" placeholder="STOCK">
                                        </div>
                                </div>
                                    
                                <div class="form-group col-md-3">
                                      <div class="p-1">
                                    <label>CODIGO</label>
                                    <input type="number" class="form-control" name="razon_social" id="codigoact" placeholder="" disabled="">
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
listar_productos();
  function listar_productos(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_PRODUCTO3"
            }
        }).done(function(data){

            json = JSON.parse(data);
            productos = json.listado;
            options = '';
            
            
             for(i = 0; i < productos.length; i++){
                if(productos[i].lote==0){
                    productos[i].lote="";
                }
                if(productos[i].stock==0){
                    productos[i].stock="";
                }
                 
                options = options + '<tr><td>'+productos[i].codigo+'</td><td>'+productos[i].nombre+'</td><td>'+productos[i].precio+'</td><td>'+productos[i].tipo_precio+'</td><td>'+productos[i].codigoafectacion+'</td><td>'+productos[i].unidad+'</td><td>'+productos[i].lote+'</td><td>'+productos[i].stock+'</td><td><button type="button" class="btn btn-danger btn-xs mr-1" onclick="eliminar(' +productos[i].codigo+ ');">Eliminar</button><button type="button" class="btn btn-primary btn-xs mr-1" onclick="editar(' +productos[i].codigo+ ')"  data-toggle="modal" data-target="#editmodal">editar</button></td></tr>';
            }

            $('#listadoproducto').html(options)
        })
    }

    
      function eliminar(id){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ELIMINAR_PRODUCTO333",
                "codigo" : id
              
            }
        }).done(function(data){
           
             
             Swal.fire({
              icon: "warning",
              title: "Eliminado...",
              text: "Se elimino un registro!",
          
            });
        })
        
        $.ajax({
        method:"GET",
        url:"view/productos.php"
        }).done(function(data){
        $('#contenido_principal').html(data)
        } 
    )
        

    }
 
    
      function editar(id){
          
          $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "EDITAR_CODIGO_PRODUCTO",
                "codigo" : id
              
            }
        }).done(function(data){
             
            json = JSON.parse(data);
            producto = json.listado;
            options = '';
            $('#codigoact').val(producto[0].codigo);
            $('#nombreact').val(producto[0].nombre);
            $('#precioact').val(producto[0].precio);
        
             $('#tipoprecioactu').val(producto[0].tipo_precio);
           document.getElementById("tipoprecioactu").innerText=producto[0].tipo_precio;
        
            $('#stockact').val(producto[0].stock);
            $('#loteact').val(producto[0].lote);
            
            $('#codigoafectacionactu').val(producto[0].codigoafectacion);
            
            if(producto[0].codigoafectacion=="10"){
            document.getElementById("codigoafectacionactu").innerText="Gravado - Operación Onerosa";
            }
            if(producto[0].codigoafectacion=="20"){
              document.getElementById("codigoafectacionactu").innerText="Exonerado - Operación Onerosa";   
            }
            if(producto[0].codigoafectacion=="30"){
               document.getElementById("codigoafectacionactu").innerText="Inafecto - Operación Onerosa";   unidadactu
            }
            
            $('#unidadactu').val(producto[0].unidad);
            
            if(producto[0].unidad=="ZZ"){
            document.getElementById("unidadactu").innerText="ZZ";
            } 
            if(producto[0].unidad=="NIU"){
              document.getElementById("unidadactu").innerText="NIU";   
            }
            
          
       
            
         
        })
          
    }
    
    function actualizar(){
            $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ACTUALIZAR_PRODUCTO",
                "codigo" : $('#codigoact').val(),
                "nombre" : $('#nombreact').val(),
                "precio" : $('#precioact').val(),
                "tipo_precio" : $('#tipoprecioact').val(),
                "codigoafectacion" : $('#codigoafectacionact').val(),
               "unidad" : $('#unidadact').val(),
                "lote" : $('#loteact').val(),
                "stock" : $('#stockact').val(),
              
            }
        }).done(function(data){
        
          //sirve para cerrar el modal.
         $('#editmodal').modal('hide');
              
                Swal.fire({
              icon: "success",
              title: "ACTUALIZADO...",
              text: "Registro Actualizado !",
          
            });
            
        }) 
        
        //irproductos();
        //Una vez guardado enviar a lista de productos
       
        
    
        
    }
    
    
    
    
</script>

