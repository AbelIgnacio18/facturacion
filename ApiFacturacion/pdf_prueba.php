<?php

require_once('model/clsCliente.php');
require_once('model/clsCompartido.php');
require_once('model/clsEmisor.php');
require_once('model/clsVenta.php');

//Objetos
$objCliente = new clsCliente();
$objCompartido = new clsCompartido();
$objVenta = new clsVenta();
$objEmisor = new clsEmisor();

//Obtenemos el ID de la venta
$id = $_GET['id'];

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
<title>BOLETA ELECTRONICA</title>
</head>
    <style>
    body {
      font-family: sans-serif;
      width: 80mm;
      /*padding: 5mm;*/
      margin: 0 auto;
      font-size: 12px;
      color: #000;
    }
    .center { text-align: center; margin-bottom:0.3rem}
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
    <div id="cabezera">
        
    </div>
    
   <div>
       <!--button type="button" onclick="imprimir()">Imprimir</button-->
   </div>
   
    <div id="mostrar_vista" style="width:100%;">
        
        <!-- LOGO -->
      <img id="logo" class="logo" alt="Logo" />
    
      <!-- CABECERA -->
      <div class="center bold" id="empresa"></div>
      <div class="center bold">RUC - <label id=ruc></label></div>
      <div class="center" id="direccion"></div>
      </br>
    
      <!-- BOLETA -->
      <div class="center bold"><label id="tipo-doc"></label> - ELECTRÓNICA</div>
      <div class="center bold" style="font-size:" id="serie-numero"></div>
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
<script src="stevstring.min.js"></script>

<script>

    const data = {
      logoBase64: 'logo_empresa2.jpg',
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
            <?php if($value['codigo'] == 6 || $value['codigo'] == 8):?>
            folio: '<?php echo $value['detalle_producto']; ?>',
            <?php endif; ?>
            <?php if($value['codigo'] == 7):?>
            habilitacion: '<?php echo $value['detalle_producto'];?>',
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
        vendedor: '<?php echo $venta['usuario'];?>'
      },
      urlConsulta: 'https://psicologos.aidvirtualizadores.com/buscar/ec9552e2-70d0-4c6c-a6c2-660ca50288c3'
    };

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
      <p style="font-weight:bold;margin-top:0px;margin-bottom:0px">CPSP: ${data.cliente.cpsp}</p>
      <p style="font-weight:bold;margin-top:0px;margin-bottom:0px"><p style="font-weight:bold;margin-top:0px;margin-bottom:0px">Fecha Emisión: ${data.cliente.fechaEmision}</p>
      <p style="font-weight:bold;margin-top:0px;margin-bottom:0px">Método pago: ${data.cliente.metodopago}</p>
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
        <td style="text-align:center;"><label>${item.descripcion}</label>
            ${ item.folio != undefined ? `<small style="font-weight:bold;font-size:11px">${item.folio}</small>` : '' }
            ${ item.habilitacion != undefined ? `<small style="font-weight:bold;font-size:11px">${item.habilitacion}</small>` : '' }
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

    document.getElementById('cabezera').innerHTML = `
      <input id="nombre_archivo" type="hidden" value="${data.serieNumero}">
    `;
    // QR
    new QRious({
      element: document.getElementById('qr'),
      size: 100,
      value: data.urlConsulta
    });
    
</script>
<script>
    
    window.onload= function(){
        
        const elemento = document.getElementById('mostrar_vista');
        const name_archivo = document.getElementById('nombre_archivo').value;
        
        const opciones ={
            margin:     0.2,
            filename:   name_archivo+".pdf",
            image:      {type: 'jpeg', quality:1},
            html2canvas:    {
                scale:  4,
                useCORS:    true
            },
            jsPDF:  {
                unit:   'in',
                format: [3.15, 9],
                orientation: 'portrait'
            }
        };
        html2pdf().set(opciones).from(elemento).save();
        
    }

</script>
</body>
</html>
