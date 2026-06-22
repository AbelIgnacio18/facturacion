<div class="col-12 mt-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">FACTURA ELECTRONICA</h3>
        </div>
        <div class="card-body">
            <form id="frmVenta" submit= "return false">
                <input type="hidden" name="accion" id="accion" value="GUARDAR_VENTA">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>EMISOR</label>
                            <select name="idemisor" id="idemisor" class="form-control"></select>
                        </div>

                        <div class="form-group">
                            <label>MONEDA</label>
                            <select name="moneda" id="moneda" class="form-control"></select>
                        </div>

                        <div class="form-group">
                            <label>FECHA EMISION</label>
                            <input type="date" class="form-control" name="fecha_emision" id="fecha_emision" value="<?php echo date('Y-m-d')?>">
                        </div>

                        <div class="form-group">
                            <label>FORMA DE PAGO</label>
                            <select name="forma_pago" id="forma_pago" class="form-control">
                                <option value="Contado">CONTADO</option>
                                <option value="Credito">CRÉDITO</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div id="div_monto_pendiente">

                            </div>
                        </div>

                    </div>


                    <div class="col-4">

                        <div class="form-group">
                            <label>COMPROBANTE</label>
                            <select name="tipocomp" id="tipocomp" class="form-control" onchange="listar_series()"></select>
                        </div>
                        
                        <div class="form-group">
                            <label>SERIE</label>
                            <select name="idserie" id="idserie" class="form-control" onchange="listar_correlativo()"></select>
                        </div>

                        <div class="form-group">
                            <label>CORRELATIVO</label>
                            <input type="number" class="form-control" name="correlativo" id="correlativo" readonly>
                        </div>

                        <div class="form-group">
                            <label>CUOTAS</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="cuotas" id="cuotas" min="1">
                                <div class="input-group-addon">
                                    <button class="btn btn-default" type="button" onclick="GenerarCuotas()">
                                        <li class="fas fa-plus" title="Generar Cuotas"></li>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="form-group">
                            <label>DOC. IDENT.</label>
                            <select name="tipodoc" id="tipodoc" class="form-control"></select>
                        </div>

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

                        <div class="form-group">
                            <label>RAZÓN SOCIAL</label>
                            <input type="text" class="form-control" name="razon_social" id="razon_social">
                        </div>

                        <div class="form-group">
                            <label>DIRECCION</label>
                            <input type="text" class="form-control" name="direccion" id="direccion">
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-4">
                        <table class="table table-hover table-sm">
                            <thead class="text-center">
                                <th>CUOTA</th>
                                <th>FECHA</th>
                                <th>MONTO</th>
                            </thead>
                            <tbody id="div_cuotas">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="search" class="form-control" name="producto" id="producto" placeholder="Buscar producto..">
                                <div class="input-group-addon">
                                    <button class="btn btn-default" type="button" onclick="BuscarProducto()">
                                        <li class="fas fa-search"></li>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <table class="table table-hover table-sm">
                                    <thead class="text-center">
                                        <th>CODIGO</th>
                                        <th>PRODUCTO</th>
                                        <th>VALOR UNITARIO</th>
                                        <th>CANTIDAD</th>
                                        <th>
                                            <button type="button" class="btn btn-default">+</button>
                                        </th>
                                    </thead>
                                    <tbody id="div_productos">

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

                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="Guardar()"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" onclick="Cancelar()"><i class="fa fa-trash-alt"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#tipocomp').val('01');
    
    listar_emisores();
    listar_monedas();
    listar_comprobantes()
    listar_documentos()
    listar_series()

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
                options = options + '<option value="' + listado[i].id + '">' + listado[i].razon_social + '</option>';            
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
                "tipo": "01"
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
                "accion": "LISTAR_DOCUMENTOS",
                "tipo": "6"
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option value="' + listado[i].codigo + '">' + listado[i].descripcion + '</option>';                               
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
                "tipocomp": "01"
            }
        }).done(function(data){

            json = JSON.parse(data);
            listado = json.listado;
            options = '';

            for (i = 0; i < listado.length; i++) {
                options = options + '<option value="' + listado[i].id + '">' + listado[i].serie + '</option>';                               
            }

            $('#idserie').html(options)
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
            $('#razon_social').val(json.result.Nombre + ' ' + json.result.Paterno + ' ' + json.result.Materno);
            $('#direccion').val('');
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
            $('#razon_social').val(json.nombre);
            $('#direccion').val('');
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
                listado = listado + '<tr><td>'+productos[i].codigo+'</td><td>'+productos[i].nombre+'</td><td>'+productos[i].precio+'</td><td><input class="form-control input-sm" id="txtCantidad'+productos[i].codigo+'" value="1" type="number" min="1" /></td><td><button type="button" class="btn btn-primary btn-sm" onclick="AgregarCarrito('+productos[i].codigo+')"> + </button></td></tr>';
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

    function Guardar(){
        var datax = $('#frmVenta').serializeArray();
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: datax
        }).done(function(resultado){
            $('#div_carrito').html(resultado);
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