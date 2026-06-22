<?php

ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);


require_once('../../ApiFacturacion/model/clsCliente.php');
require_once('../../ApiFacturacion/model/clsCompartido.php');
require_once('../../ApiFacturacion/model/clsEmisor.php');
require_once('../../ApiFacturacion/model/clsVenta.php');

//Objetos
$objCliente = new clsCliente();
$objCompartido = new clsCompartido();
$objVenta = new clsVenta();
$objEmisor = new clsEmisor();

//Obtenemos el ID de la venta
$id = $idboleta;

$venta = $objVenta->obtenerComprobanteId($id);
$venta = $venta->fetch(PDO::FETCH_NAMED);

$detalle = $objVenta->listarDetalleComprobanteId($id);
$detalle = $detalle->fetchAll(PDO::FETCH_NAMED);

$emisor = $objEmisor->obtenerEmisor($venta['idemisor']);
$emisor = $emisor->fetch(PDO::FETCH_NAMED);

$tipo_comprobante = $objCompartido->obtenerComprobante($venta['tipocomp']);
$tipo_comprobante = $tipo_comprobante->fetch(PDO::FETCH_NAMED);

$cliente = $objCliente->consultarClientePorCodigo($venta['codcliente']);
$cliente = $cliente->fetch(PDO::FETCH_NAMED);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LISTA DE ASISTENCIA</title>
</head>
    <style>
    
    #mostrar_vista {
      font-family: sans-serif;
      
      /*padding: 5mm;*/
      margin: 0 auto;
      font-size: 12px;
      color: #000;
    }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    hr {
      border: none;
      border-top: 1px dashed #000;
      margin: 5px 0;
      
      
      
    }
    table {
      width: 100%;
      border-collapse: collapse;
      
      
      
    }
    table td{padding-bottom: 0.4rem}
    table td small{display:block}
    thead th{padding-bottom: 0.4rem; text-align:left;}
    .right { text-align: right; padding-left: .4rem;}
    .logo {
      width: 60px;
      height: auto;
      margin: 0 auto 5px;
      display: block;
    }
    #link-consulta{word-break: break-all;max-width: 100%}
    canvas {
      display: block;
      margin: 10px auto;
      
    }
  </style>
<body>
   <div>
       <!--button type="button" onclick="imprimir()">Imprimir</button-->
   </div>
   
    <div class="card" id="mostrar_vista" style="width:100%;">
        <!-- LOGO -->
      <img id="logo" class="logo" alt="Logo" />
    
      <!-- CABECERA -->
      <div class="center bold" id="empresa"></div>
      <div class="center bold">RUC - <label id=ruc></label></div>
      <div class="center" id="direccion"></div>
      
    
      <!-- BOLETA -->
      <div class="center bold"><label id="tipo-doc"></label> - ELECTRÓNICA</div>
      <div class="center bold" id="serie-numero"></div>
      </br>
    
      <div id="datos-cliente"></div>
      <hr />
    
      <!-- DETALLE PRODUCTOS -->
      <table id="detalle">
        <thead>
          <tr>
            <th>Cant.</th>
            <th style="text-align:center">Descripción</th>
            <th>P.Unit</th>
            <th class="right">Total</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    
      <hr />
    
      <!-- TOTALES -->
      <div class="right" id="totales"></div><br>
      <div class="right"><strong>Son:</strong> <span id="monto-letras"></span></div>
      <hr />
    
      <!-- OTROS DATOS -->
      <div id="otros-datos"></div>
      </br>
    
      <!-- ENLACE Y QR -->
      <div class="center" id="link-consulta"></div>
      <div class="center"><canvas id="qr"></canvas></div>
</div>

<!-- Librerías -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="../../ApiFacturacion/stevstring.min.js"></script>

<script>

    const data = {
      logoBase64: '../ApiFacturacion/logo_empresa2.jpg',
      empresa: `<?php echo $emisor['razon_social'];?>`,
      ruc: '<?php echo $emisor['nrodoc']; ?>',
      direccion: '<?php echo $emisor['direccion'];?>',
      tipoDocumento: '<?php echo $tipo_comprobante['descripcion']; ?>',
      serieNumero: `<?php echo $venta['serie'] ?>-<?php echo $venta['correlativo']?>`,
      cliente: {
        nombre: '<?php echo $cliente['razon_social'];?>',
        dni: '<?php echo $cliente['nrodoc'];?>',
        direccion: '<?php echo $cliente['direccion'];?>',
        cpsp: '<?php echo $cliente['codigo'];?>',
        fechaEmision: '<?php echo date("d/m/Y", strtotime($venta["fecha_emision"])); ?>',
        metodopago: '<?php echo $venta['tipo_operacion'] ?>',
        <?php if($venta['tipo_operacion'] == "Transferencia"): ?>
        numoperacion: <?php echo $venta['numero_operacion'] ?>,
        fechaOperacion: '<?php echo date("d/m/Y", strtotime($venta["fecha_operacion"])); ?>',
        <?php endif;?>
        moneda: '<?php echo $venta['codmoneda'] ?>',
      },
      productos: [
        <?php foreach ($detalle as $value): ?>
            { 
            cantidad: <?php echo $value['cantidad']; ?>, 
            descripcion: '<?php echo $value['nombre']; ?>',
            <?php if($value['codigo'] == 6 || $value['codigo'] == 8): $fnum= (int)$value['lote']-(int)$value['cantidad']+1;?>
            folio: '<?php echo (int)$value['cantidad'] == 1 ? $fnum : $fnum.' al '. ($fnum+(int)$value['cantidad']-1) ?>',
            <?php endif; ?>
            <?php if($value['codigo'] == 7):
            date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
            $fecha1 = strtotime($cliente['fecha_vigencia']);
            $fecha_temp = date('Y-m-d',strtotime(date("Y-m-d",$fecha1). ' - 90 days'));
            $fecha2 = strtotime($fecha_temp);
            $num = (int)$value['cantidad'];
            $fecha_temp2  = date('Y-m-d',strtotime(date("Y-m-d",$fecha2). ' - '.$num.' month'));
            $fecha_desde = strftime('%Y-%m-%d', strtotime($fecha_temp2));
                        ?>
            pago: '<?php echo $fecha_desde;?>',
            hasta: '<?php echo $fecha_temp; ?>',
            
            f_actual: '<?php echo $cliente['fecha_vigencia'];?>',
            <?php endif;?>
            precio: <?php echo $value['valor_unitario']; ?>, 
            total: <?php echo $value['valor_total']; ?> 
            },
        <?php endforeach; ?>
      ],
      totales: { 
        gravada: <?php echo $venta['op_gravadas'];?>,
        exoneradas: <?php echo $venta['op_exoneradas'];?>,
        inafectas: <?php echo $venta['op_inafectas'];?>,
        igv: <?php echo $venta['igv'];?>,
        total: <?php echo $venta['total']?>,
        enLetras: stevstring(<?php echo $venta['total'];?>)
      },
      otros: {
        hash: 'JMALCMdshrCfjecLOZKpJyKQ/Ek=',
        vendedor: '<?php echo $venta['serie'] == 'B001' ? 'RUTH' : 'MARÍA' ?>'
      },
      urlConsulta: 'https://psicologos.aidvirtualizadores.com/buscar/ec9552e2-70d0-4c6c-a6c2-660ca50288c3'
    };


    if(5==5){
       document.getElementById('logo').src = data.logoBase64;
    document.getElementById('empresa').innerHTML = data.empresa;
    document.getElementById('ruc').innerHTML = data.ruc;
    document.getElementById('direccion').innerHTML = data.direccion;
    document.getElementById('tipo-doc').innerHTML = data.tipoDocumento;
    document.getElementById('serie-numero').innerHTML = data.serieNumero;

    document.getElementById('datos-cliente').innerHTML = `
      Cliente: ${data.cliente.nombre}<br>
      DNI: ${data.cliente.dni}<br>
      Dirección: ${data.cliente.direccion}<br>
      CPSP: ${data.cliente.cpsp}<br>
      Fecha Emisión: ${data.cliente.fechaEmision}<br>
      Método pago: ${data.cliente.metodopago}<br>
      ${data.cliente.numoperacion != undefined ? `Número operación: ${data.cliente.numoperacion}<br>` : '' }
      ${data.cliente.fechaOperacion != undefined ? `Fecha operación: ${data.cliente.fechaOperacion}<br>` : '' }
      Moneda: ${data.cliente.moneda == "PEN" ? "Soles" : "Dolares"}
    `;
    
    //const datafolio = `<small>Folio:${item.folio}</small>`;
    const tbody = document.querySelector('#detalle tbody');
    data.productos.forEach(item => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${item.cantidad}</td>
        <td style="text-align:center"><label>${item.descripcion}</label>
            ${ item.folio != undefined ? `<small>Folio: ${item.folio}</small>` : '' }
            ${ item.pago != undefined ? `<small>Pago: ${item.pago}</small>` : '' }
            ${ item.hasta != undefined ? `<small>Hasta: ${item.hasta}</small>` : '' }
            ${ item.f_actual != undefined ? `<small>Nueva Vigencia: ${item.f_actual}</small>` : '' }
        </td>
        <td>${item.precio.toFixed(2)}</td>
        <td class="right">${item.total.toFixed(2)}</td>
      `;
      tbody.appendChild(row);
    });

    document.getElementById('totales').innerHTML = `
      Op. Gravadas: S/ ${data.totales.gravada.toFixed(2)}<br>
      Op. Exoneradas: S/ ${data.totales.exoneradas.toFixed(2)}<br>
      Op. Inafectas: S/ ${data.totales.inafectas.toFixed(2)}<br>
      IGV: S/ ${data.totales.igv.toFixed(2)}<br>
      <strong>Total a pagar: S/ ${data.totales.total.toFixed(2)}</strong>
    `;
    document.getElementById('monto-letras').textContent = data.totales.enLetras;

    document.getElementById('otros-datos').innerHTML = `
      Código hash:${data.otros.hash}<br>
      Vendedor: ${data.otros.vendedor}
    `;

    document.getElementById('link-consulta').innerHTML = `
      Representación impresa de la BOLETA DE VENTA ELECTRÓNICA<br>
      Puede ser consultada en:<br>
      <a href="${data.urlConsulta}" target="_blank">${data.urlConsulta}</a>
    `; 
    }
        
    

     


   

    // QR
    new QRious({
      element: document.getElementById('qr'),
      size: 100,
      value: data.urlConsulta
    });
    
</script>
<script>
    
   
    
    document.addEventListener("DOMContentLoaded", function() {
        alert("Boleta abierta");
    })

</script>
</body>
</html>
