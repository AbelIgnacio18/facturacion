<?php 
    session_start();
?>
<div class="col-12 mt-1">
    <form id="frmVenta" submit= "return false" >
    <div class="card" >
        
        <div class="card-header bg-primary" id="cabezera1">
            <h3 class="card-title ">BOLETA ELECTRONICA ( <a id="boleta_serie"></a><a id="boleta_correlativo"></a> ) </h3>
        </div>
        
        <div id="respuesta">
            
        </div>
        <div class="card-body"  >
            
                <input type="hidden" name="accion" id="accion" value="GUARDAR_VENTA">
                <input type="hidden" name="userboleta" id="userboleta" value="<?php echo $_SESSION['usuario'];?>">
                <section id="datos_boleta">
                    <div class="d-none row">
                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label>EMISOR</label>
                                <select name="idemisor" id="idemisor" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row d-none"><!-- OCULTARE TODA ESTA FILA CORRESPONDIENTE A BOLETA COMPROBANTE SERIE CORRELATIVO Y FECHA DE EMISIÓN -->
                        
                   <div class="col-lg-4 col-sm-12">
                        <div class=" form-group">
                            <label>COMPROBANTE</label>
                            <select name="tipocomp" id="tipocomp" class="form-control" onchange="listar_series()"></select>
                        </div>
                        </div>
                    <div class=" col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>SERIE</label>
                            <select name="idserie" id="idserie" class="form-control" onchange="listar_correlativo()"></select>
                        </div>
                        <div class="d-none form-group">
                            <label>CORRELATIVO</label>
                            <input type="number" class="form-control" name="correlativo" id="correlativo" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                         <div class="form-group">
                            <label>FECHA EMISION</label>
                            <input type="date" class="form-control" name="fecha_emision" id="fecha_emision" value="<?php date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');;echo date('Y-m-d')?>">
                        </div>
                    </div>
                </div>
                
                <div class="row d-none"><!-- OCULTARE TODA ESTA FILA CORRESPONDIENTE MONEDA DOC-IDENTIDAD DIRECCIÓN  -->
                    <div class="col-lg-4 col-sm-12">
                       <div class="form-group">
                            <label>MONEDA</label>
                            <select name="moneda" id="moneda" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>DOC. IDENT.</label>
                            <select name="tipodoc" id="tipodoc" class="form-control"></select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>DIRECCION</label>
                            <input type="text" class="form-control" name="direccion" id="direccion">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>COLEGIATURA</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nrocol" id="nrocol"  autofocus >
                                <div class="input-group-addon">
                                    <button class="btn btn-default" type="button" onclick="ObtenerColegiatura()">
                                        <li class="fas fa-search" title="Buscar"></li>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>NRO DOC.</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nrodoc" id="nrodoc" >
                                <div class="input-group-addon">
                                    <button class="btn btn-default" type="button" onclick="ObtenerDatosEmpresa()">
                                        <li class="fas fa-search" title="Buscar"></li>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>RAZON SOCIAL</label>
                            <input type="text" class="form-control" name="razon_social" id="razon_social" >
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>TIPO DE PAGO</label>
                            <select name="tipo_pago" id="tipo_pago" class="form-control">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group d-none" id="group_operacion_num">
                            <label>NRO. OPERACIÓN</label>
                            <input type="number" class="form-control" name="nro_operacion" id="nro_operacion" value="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="form-group d-none" id="group_operacion_date">
                            <label>FECHA OPERACIÓN</label>
                            <input type="date" class="form-control" name="fecha_operacion2" id="fecha_operacion2" value="">
                        </div>
                    </div>
                    <input type="hidden" name="forma_pago" id="forma_pago"  value="contado">
                </div>
                </section>
                
                <div class="row justify-content-end">
                    <div class="col-6 card-footer text-left">
                        <h6 class="my-0" id="resumen_cliente"></h6>
                        
                        <div id="documento"></div>
                    </div>    
                    <div class="col-6 card-footer text-right">
                            <h6 class="pr-5 my-0 text-right" id="resumen_tipo_pago" style="height:0px;float:right;font-weight:bold;">DETALLE DE VENTA<p style='margin-bottom: 0px;'><small>Efectivo</small></p></h6>
                            <button style="" id="btnboleta" type="button" class="btn btn-light " onclick="seleccionar_detalle()"><i class="fa fa-shopping-cart"></i></label>
                            <button style="" id="btndetalle" type="button" class="btn btn-light d-none " onclick="seleccionar_boleta()"><i class="fa fa-sticky-note"></i></button>
                    </div>
                </div>
                
            
                <section class="d-none" id="datos_detalle">
                    <div class="row pt-3" >
                    <div class="col-6">
                        <div class="">
                            <div class="">
                                <a id="borrar_filtro" class="d-none bg-white pr-1 pl-3 " type="button" onclick="borrar_filtro()" style="font-size:20px;position:absolute;right:15px;top:5px;color:#274271;-webkit-text-stroke-width: 0.2px;-webkit-text-stroke-color: #657999;" ><small class="fa fa-times"></small></a>
                                <input type="search" class="form-control" name="filtro_producto" id="filtro_producto" placeholder="Escriba el filtro; para buscar producto">
                            </div>
                            <div>
                                <table class="table table-hover table-sm" id="geeks">
                                    
                                    <thead class="text-center">
                                        <th>CODIGO</th>
                                        <th>PRODUCTO</th>
                                        <th>VALOR UNITARIO</th>
                                        <th>CANTIDAD</th>
                                    </thead>
                                    
                                    <tbody id="div_productos" >

                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        
                        <div class="col-12" id="div_carrito">
                            
                        </div>
                        
                    </div>

                </div>
                </section>
                
                    
                

                
            
        </div>
        <div id="btncarrito2" class="d-none card-footer justify-content-end text-right">
                        <button type="button" class="btn btn-primary" onclick="Guardar()" ><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" onclick="Cancelar()"><i class="fa fa-trash-alt"></i> Cancelar</button>
                        
        </div>
        <div id="btnnuevaventa" class="d-none card-footer justify-content-end text-right">
                        
                        <button class='btn btn-primary' onclick='nuevaBoleta()' type='button'>NUEVA VENTA</button>
        </div>
        
    </div>
    </form>
</div>
<iframe id="printFrame" name="printFrame" style="display:none;"></iframe>

<script>
    function imprimirConstancia(id) {
        const iframe = document.getElementById('printFrame');
        iframe.src = './ApiFacturacion/constancia.php?id=' + id;
        iframe.onload = function() {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        };
    }
    
   
    
    function seleccionar_detalle(){
    
        nombre = document.getElementById("razon_social").value;
        if(nombre !== "" ){
            $('#datos_boleta').addClass('d-none');
            $('#datos_detalle').removeClass('d-none');
            $('#btndetalle').removeClass('d-none');
            $('#btnboleta').addClass('d-none');
            document.getElementById("div_carrito").innerHTML = "";
        }else{
            alert("Busca el Nombre");
        }
        
        if(document.getElementById("tipo_pago").value == "Efectivo" ){
            nro_operacion = "";
            fecha_operacion2 ="";
        }else{
            nro_operacion = ": N°"+document.getElementById("nro_operacion").value ;
            fecha_operacion2 ="/"+document.getElementById("fecha_operacion2").value;
        }
        
        
        pago = "<p style='margin-bottom: 0px;'><small>"+document.getElementById("tipo_pago").value+nro_operacion+fecha_operacion2+"</small></p>" ;
        var datos_pago = 'DETALLE DE VENTA'+pago;
        document.getElementById("resumen_tipo_pago").innerHTML = datos_pago;
        $('#btncarrito2').removeClass('d-none');
        $('#filtro_producto').val("");
        BuscarProducto();
        $('#filtro_producto').focus();
        
        
    }
    
    function seleccionar_boleta(){
            $('#datos_boleta').removeClass('d-none');
            $('#datos_detalle').addClass('d-none');
            $('#btndetalle').addClass('d-none');
            $('#btnboleta').removeClass('d-none')
            $('#btncarrito2').addClass('d-none');
            Cancelar();
            
    }
    
    function borrar_filtro(){
        $('#filtro_producto').val("");
        BuscarProducto();
        $('#filtro_producto').focus();
        $('#borrar_filtro').addClass("d-none");
    }
    
    $(document).ready(function() {
      $('#tipo_pago').change(function(e) {
        if ($(this).val() === "Efectivo") {
          $('#frmVenta #group_operacion_num').addClass('d-none')
          $('#frmVenta #group_operacion_num').removeClass('d-block')
          $('#frmVenta #group_operacion_date').addClass('d-none')
          $('#frmVenta #group_operacion_date').removeClass('d-block')
          
        } else {
          $('#frmVenta #group_operacion_num').addClass('d-block')
          $('#frmVenta #group_operacion_num').removeClass('d-none')
          $('#frmVenta #group_operacion_date').addClass('d-block')
          $('#frmVenta #group_operacion_date').removeClass('d-none')
          $('#nro_operacion').val('')
          $('#fecha_operacion2').val('')
        }
        pago = "<p style='margin-bottom: 0px;'><small>"+document.getElementById("tipo_pago").value+"</small></p>";
        var datos_pago = 'DETALLE DE VENTA'+pago;
        document.getElementById("resumen_tipo_pago").innerHTML = datos_pago;
      })
    });
    
$('#tipocomp').val('03');
    
    listar_emisores();
    listar_monedas();
    listar_comprobantes();
    listar_documentos();
    listar_series();
    BuscarProducto();
    function listar_emisores(){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "LISTAR_EMISORES"
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';
            
            
            for (let i = 0; i < listado.length; i++) {
                options = options + '<option value="' + listado[i].id + '">' + listado[i].razon_social + '</option>' ;            
            }
            $('#idemisor').html(options) 
        })
    }

     function listar_monedas(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_MONEDAS"
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option value="' + listado[i].codigo + '">' + listado[i].descripcion + '</option>';                               
            }

            $('#moneda').html(options)
        })
    }
 
    function listar_comprobantes(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_COMPROBANTES",
                "tipo": "03"
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option value="' + listado[i].codigo + '">' + listado[i].descripcion + '</option>';                               
            }

            $('#tipocomp').html(options)
        })
    }

    function listar_documentos(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_DOCUMENTOS_TODOS"
                
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                if(i==1){
                    seleccion_doc = "selected";
                }else{
                    seleccion_doc = "";
                }
                options = options + '<option '+seleccion_doc+' value="' + listado[i].codigo + '">' + listado[i].descripcion + '</option>';                               
            }

            $('#tipodoc').html(options)
        })
    }

    function listar_series(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "LISTAR_SERIES",
                "tipocomp": "03",
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option style="float:left" value="' + listado[i].id + '">' + listado[i].serie + '</option>';                               
            }

            $('#idserie').html(options)
            $("#boleta_serie").html(listado[0].serie+" - ");
            listar_correlativo();
        })
    }

    function listar_correlativo(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "OBTENER_CORRELATIVO",
                "idserie": $('#idserie').val()
            }
        }).done(function(data){
            $('#correlativo').val(data)
             $("#boleta_correlativo").html(data);
        })
    }

    function ObtenerDatosEmpresa(){
        tipodoc = $('#tipodoc').val();
        if(tipodoc == 1){
            ObtenerDatosDNI();
        }else{
            ObtenerDatosRUC();
        }
    }

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
            
            razon_social = document.getElementById("razon_social").value;
            direccion = document.getElementById("direccion").value;
            nro_doc = document.getElementById("nrodoc").value;
            var datos_cliente = '<b>Cliente : '+nro_doc + '- '+razon_social +  '</b><p style="margin-bottom: 0px;"><small> Dirección: '+ direccion + '</small></p>' ;
            document.getElementById("resumen_cliente").innerHTML = datos_cliente;
            
        })
    }
    
    function ObtenerDatosRUC(){
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "CONSULTA_RUC",
                "ruc" : $('#nrodoc').val()
            }
        }).done(function(text){

            json = JSON.parse(text);
            $('#razon_social').val(json.nombre)
        })
    }
    
    function ObtenerColegiatura(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "OBTENER_COLEGIADO",
                "codigocol": $('#nrocol').val()
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;

            $('#razon_social').val(listado[0].razon_social);
            $('#nrodoc').val(listado[0].nrodoc);
            $('#direccion').val(listado[0].direccion)
            razon_social = document.getElementById("razon_social").value;
            direccion = document.getElementById("direccion").value;
            cod_colegiado = document.getElementById("nrocol").value;
            if(cod_colegiado == ""){
               cod_colegiado = "";
            }else{
                cod_colegiado = "C.Ps.P N°: "+cod_colegiado + " - ";
            }
            var datos_cliente = '<b>'+cod_colegiado + ' '+razon_social +  '</b><p style="margin-bottom: 0px;"><small> Dirección: '+ direccion + '</small></p>' ;
            document.getElementById("resumen_cliente").innerHTML = datos_cliente;
            
        })
    }

    function BuscarProducto(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "BUSCAR_PRODUCTO",
                "filtro": $('#producto').val()
            }
        }).done(function(data){
            json = JSON.parse(data);
            productos = json.listado;
            listado = '';
            for(i = 0; i < productos.length; i++){
                listado = listado + '<tr><td>'+productos[i].codigo+'</td><td>'+productos[i].nombre+'</td><td>'+productos[i].precio2+'</td><td><input class="form-control input-sm" id="txtCantidad'+productos[i].codigo+'" value="1" type="number" min="1" /></td><td><button type="button" class="btn btn-primary btn-sm" onclick="AgregarCarrito('+productos[i].codigo+')"> + </button></td></tr>';
            }

            $('#div_productos').html(listado);
        })
    }
    

    function AgregarCarrito(codigo){
        
        $.ajax({
            method:"POST",
            url:"ApiFacturacion/controller/controlador.php",
            data: {
                "accion" : "ADD_PRODUCTO",
                "codigo" : codigo,
                "cliente" : $('#nrodoc').val(),
                "cantidad" : $('#txtCantidad' + codigo).val()
            }
        }).done(function(text){
            $('#div_carrito').html(text);
        })

    }

    function Cancelar(){
        
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "CANCELAR_CARRITO"
            }
        }).done(function(resultado){
            $('#div_carrito').html(resultado);
            
        })
    }
    
    function nuevaBoleta(){
        seleccionar_boleta();
        listar_emisores();
        listar_monedas();
        listar_comprobantes();
        listar_documentos();
        listar_series();
        $('#nrocol').val('');
        $('#nrodoc').val('');
        $('#razon_social').val('');
        $('#direccion').val('');
        $('#nro_operacion').val('');
        $('#fecha_operacion2').val('');
        document.getElementById("resumen_cliente").innerHTML="";
        $('#btnnuevaventa').addClass('d-none');
        document.getElementById("filtro_producto").value="";
        $('#respuesta').html("");
        $('#cabezera1').removeClass("d-none");
    }
    
    function Guardar(){
        
        var datax = $('#frmVenta').serializeArray();
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: datax
        }).done(function(resultado){
            
            $('#respuesta').html(resultado);
            $('#cabezera1').addClass("d-none");
            
            
            $('#btncarrito2').addClass("d-none");
            $('#btnnuevaventa').removeClass("d-none");
            
            
            
            
            
            
            //Cuando todo esta bien pegar codigo de redireccion para boleta
            //let elemento = $('[role="alert"].alert-success').attr('id');
            //let iframe = document.createElement('iframe');
            //iframe.style.display = 'none';
            //iframe.src = './ApiFacturacion/pdf_prueba.php?id=251';
            //iframe.onload = function () {
                //console.log('PDF generado correctamente desde iframe');
            //};
            //document.body.appendChild(iframe)
            
            
        })
        
    }

    function GenerarCuotas(){
        listado = '';
        cuotas = $('#cuotas').val()
        for (let i = 1; i <= cuotas; i++) {
            listado = listado + '<tr><td><input class="form-control input-sm" name="txtCuota' + i +'" type="text" value="Cuota ' + i + '" readonly/></td>'
                        + '<td><input class="form-control input-sm" name="txtFecha' + i +'" type="date"/></td>'
                        + '<td><input class="form-control input-sm" name="txtMonto' + i +'" type="number"/></td></tr>';
        }

        $('#div_cuotas').html(listado);

        if (cuotas > 0) {
            monto_pendiente = '<div class="form-group"><label>Monto pendiente</label><input class="form-control" type="number" name="monto_pendiente" id="monto_pendiente" value="0.0" /></div>';
            $('#div_monto_pendiente').html(monto_pendiente);
        }
    }






</script>

<script>
            $(document).ready(function() {
                $("#filtro_producto").on("keyup", function() {
                    $('#borrar_filtro').removeClass("d-none");
                    var value = $(this).val().toLowerCase();
                    $("#geeks tr").filter(function() {
                        $(this).toggle($(this).text()
                        .toLowerCase().indexOf(value) > -1)
                    }); 
                });
            });
</script>