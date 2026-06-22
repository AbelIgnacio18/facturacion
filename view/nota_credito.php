<?php
 
require_once('../ApiFacturacion/model/clsEmisor.php');
require_once('../ApiFacturacion/model/clsCompartido.php');

$objEmisor = new clsEmisor();
$objCompartido = new clsCompartido();

$listado = $objEmisor->consultarListaEmisores();

$monedas = $objCompartido->listarMonedas();

$comprobantes = $objCompartido->listarComprobantesCodigo('07');
$comprobantes = $comprobantes->fetchAll(PDO::FETCH_NAMED);

$comprobantesNotas = $objCompartido->listarComprobantesNotas();
$comprobantesNotas = $comprobantesNotas->fetchAll(PDO::FETCH_NAMED);

$documentos = $objCompartido->listarTipoDocumento();

$motivos = $objCompartido->listarTablaParametrica("C");

?>


<section class="content mt-3 w-100">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"> <i class="fas fa-shopping-cart"></i> NOTA DE CRÉDITO ( <a id="nota_serie"></a><a id="nota_correlativo"></a> ) </h3>

              </div>
              <div class="card-body">
				
				<form id="frmVenta" name="frmVenta" submit="return false">
                    <div class="col-12 ">
                        <div class="row ">
                            
                                    <input class="d-none" type="text" name="idboleta" id="idboleta" >
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label>Serie Boleta</label>
                                            <select name="serie_ref2" id="serie_ref2" class="form-control" ></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label>Correlativo Boleta</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" name="correlativo_ref2" id="correlativo_ref2" />
                                                <div class="input-group-addon">
                                                    <button type="button" class="btn btn-default" onclick="BuscarBoleta()"><li class="fa fa-search"></li></button>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                
                                
                                
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label>Motivo.</label>
                                            <select class="form-control" name="motivo" id="motivo">
                                            <?php foreach($motivos as $key=>$fila){ ?>
                                                <option  value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-sm-12 align-self-end  " >
                                        <div class="form-group  ">
                                            <button type="button" class="btn btn-primary" onclick="GuardarVenta()"  ><i class="fa fa-save"></i> GUARDAR</button> 
                                        </div>
                                    </div>
                                    
                        </div>
                        
                        
                        <div class="row justify-content-center">
                                    <div class="card col-lg-3 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label>Tipo Comp.</label>
                                            <select class="form-control" name="tipocomp" id="tipocomp" onchange="ConsultarSerie()">
                                                <?php foreach($comprobantes as $k=>$fila){ ?>
                                                    <option value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Serie</label>
                                            <select class="form-control" type="date" name="idserie" id="idserie" onchange="ConsultarCorrelativo()">
                                            </select>
                                            <input type="hidden" name="accion" id="accion" value="GUARDAR_NC">
                                        </div>
                                        <div class="form-group">
                                            <label>Correlativo</label>
                                            <input class="form-control" type="number" name="correlativo" id="correlativo" />
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input class="form-control" type="date" name="fecha_emision" id="fecha_emision" value="<?php echo date('Y-m-d');?>" />
                                        </div>
                                        <div class="form-group">
                                            <label>Moneda</label>
                                            <select class="form-control" type="date" name="moneda" id="moneda">
                                                <?php while($fila = $monedas->fetch(PDO::FETCH_NAMED)){ ?>
                                                    <option value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Documento Ref.</label>
                                            <select class="form-control" name="tipocomp_ref" id="tipocomp_ref">
                                                <?php foreach($comprobantesNotas as $k=>$fila){ ?>
                                                <option <?php if($fila['codigo']=="03"){echo "selected";} ?> value="<?php echo $fila['codigo'];?>"><?php echo $fila['descripcion'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    <div class="card col-lg-4 col-sm-12 " id="boleta">
                                        
                                    </div>
                        </div>
                </div>
                </form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                
              </div>
              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>



<script>
	
  $("#tipocomp").val("07");
  ConsultarSerie();
  listar_seriesBoleta();

  function ConsultarSerie(){
      $.ajax({
          method: "POST",
          url: 'ApiFacturacion/controller/controlador.php',
          data: {
          	  "accion": "LISTAR_SERIES",
              "tipocomp": "07"
            }
      })
      .done(function( text ) {
            json = JSON.parse(text);            
            series = json.listado;
            options = '';
            for(i=0;i<series.length;i++){
            	options = options + '<option value="'+series[i].id+'">'+series[i].serie+'</option>';
            }
            $("#idserie").html(options);
            $("#nota_serie").html(series[0].serie+" - ");
            ConsultarCorrelativo();
      });
  }
  
  function ConsultarCorrelativo(){
      
      $.ajax({
          method: "POST",
          url: 'ApiFacturacion/controller/controlador.php',
          data: {
          	  "accion": "OBTENER_CORRELATIVO",
              "idserie": $("#idserie").val()
            }
      })
      .done(function( correlativo ) {
            $("#correlativo").val(correlativo);
            $("#nota_correlativo").html(correlativo);
      });
  }
  
  function listar_seriesBoleta(){
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

            $('#serie_ref2').html(options)
            
        })
    }
    
    function BuscarBoleta(){
        
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "BUSCAR_BOLETA",
                "serie": $('#serie_ref2').val(),
                "correlativo" : $('#correlativo_ref2').val()
            }
        }).done(function(data){
            json = JSON.parse(data);
            
                
                $("#idboleta").val(json.idboleta);
                
                
                let htmlBoleta = `
                
            
            <style>
            #font_boleta{
            font-family: sans-serif;
            margin: 0 auto;
            font-size: 12px;
            color: #000;
            margin:20px;
            }
            hr {
                border: none;
                border-top: 1px dashed #000;
                margin: 5px 0;
            }
            label{
                font-weight:bold;
                text-align:center;
                width:100%;
                margin-bottom:0px;
            }
            #totales{
                text-align:right;
            }
            p{
                margin-bottom:0px;
            }
            .logo {
              width: 80px;
              height: auto;
              margin: 0 auto 5px;
              display: block;
            }
            </style>
            
            
        <div id="font_boleta">
            
            <img class="logo" alt="Logo" src="../ApiFacturacion/logo_empresa2.jpg">
            <label>${json.empresa}</label>
            <label>RUC - ${json.ruc}</label>
            <div class="text-center px-3 mt-1 mb-3">Dirección: ${json.direccion_emisor}</div>
            <label>${json.tipoDocumento} - ELECTRÓNICA</label>
            <label>${json.serieNumero}</label>
            </br></br>
            <p>Nombre: ${json.nombre}</p>
            <p>DNI: ${json.dni}</p>
            <p>Dirección: ${json.direccion_cliente}</p>
            <p>CPSP: ${json.codigo}</p>
            <p>Fecha Emisión: ${json.fecha_emision}</p>
            <p>Método pago: ${json.metodo_pago}</p>
            ${json.detalle_operacion}
            <p>Moneda: ${json.codmoneda}</p>
            <hr>
            <table class="  w-100 px-2">
                <thead><tr><th>Cant.</th><th>Descripción</th><th>P.Unit</th><th>Total</th></tr></thead>
                <tbody>
                ${json.productos.map(item => `
                    <tr>
                        <td>${parseInt(item.cantidad)}</td>
                        <td>${item.nombre}</br>${item.detalle_producto}</td>
                        <td>${parseFloat(item.valor_unitario).toFixed(2)}</td>
                        <td>${parseFloat(item.valor_total).toFixed(2)}</td>
                    </tr>
                `).join('')}
                </tbody>
            </table>
            <hr>
            <div id="totales">
            <p>Op.Gravadas: S/ ${parseFloat(json.gravada).toFixed(2)}</p>
            <p>Op.Exoneradas: S/ ${parseFloat(json.exoneradas).toFixed(2)}</p>
            <p>Op.Inafectas: S/ ${parseFloat(json.inafectas).toFixed(2)}</p>
            <p>IGV: S/ ${parseFloat(json.igv).toFixed(2)}</p>
            <p class="font-weight-bold">Total a pagar: S/ ${parseFloat(json.total).toFixed(2)}</p>
            </br>
            <p>Son: ${json.total_letras}</p>
            </div>
            <hr>
            <p>Código hash: ${json.codigo_hash}</p>
            <p>Vendedor: ${json.vendedor}</p>
            <p class="text-center px-0">REPRESENTACIÓN DE BOLETA DE VENTA ELECTRÓNICA</p>
            <p class="text-center px-3">Puede ser consultada en:</p>
            <p class="text-center"><a   href="${json.urlConsulta}" target="_blank">https//consulta.sunat.gob.pe</a></p>
            <img class="logo" alt="Logo" src="../ApiFacturacion/QR.PNG">
        </div>
        `;
        

        $('#boleta').html(htmlBoleta);
            
            
        })
        
        
    }

  
  
  function GuardarVenta(){
  	var datax = $("#frmVenta").serializeArray();

	$.ajax({
      method: "POST",
      url: 'ApiFacturacion/controller/controlador.php',
      data: datax
  	})
  	.done(function(resultado) {
        $("#boleta").html(resultado);
  	}); 

  }

</script>

