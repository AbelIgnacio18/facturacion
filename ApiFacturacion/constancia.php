<?php
require_once('model/clsCliente.php');
require_once('model/clsVenta.php');

$objCliente = new clsCliente();
$objVenta = new clsVenta();

//Obtenemos el ID de la venta
$id = $_GET['id'];

$venta = $objVenta->obtenerComprobanteId($id);
$venta = $venta->fetch(PDO::FETCH_NAMED);

$detalle = $objVenta->listarDetalleComprobanteId($id);
$detalle = $detalle->fetchAll(PDO::FETCH_NAMED);


$cliente = $objCliente->consultarClientePorCodigo($venta['codcliente']);
$cliente = $cliente->fetch(PDO::FETCH_NAMED);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Constancia de Habilitación Profesional</title>
  <style>
    @page {
      size: A4;
      margin: 0;
    }
    body {
      font-family: "Arial", serif;
      width: 595px;
      margin-right: 100px;
      margin-left: 100px;
      margin-top: 260px;
      position: relative;
      font-size: 12pt;
      line-height: 1.4;
      color: #211f1f;
    }
    .content-body {
      text-align: justify;
      line-height: 1.1;
    }
    .nro-top{margin-left:65px; font-size:18px;margin-bottom:18px;}
    .content {
      font-size: 16px;
      line-height: 1.4;
    }
    .dynamic-constancia{font-family: "Times New Roman", serif;}
    .signature {
      margin-top: 22px;
      text-align: right;
      font-size:17px;
    }
    .colegio-detalles{font-size: 12px;
    width: 60%; text-align:center; margin-bottom:0}
    .detalle-justi{    justify-content: center;
    display: flex;margin-top:22px}
    .footer {
      margin-top: 22px;
      text-align: right;
    }
    .seal {
      position: absolute;
      bottom: 40px;
      left: 40px;
      width: 100px;
      height: 100px;
      background: url('https://upload.wikimedia.org/wikipedia/commons/0/06/Seal_of_the_Psychologists_of_Peru.png') no-repeat center;
      background-size: contain;
    }
    
    .style_agremiado{
        font-family:  Sans-Serif;
    }
    
    .dynamic {
         
      font-weight: bold;
      text-transform: uppercase;
      text-align: center;
      margin-top:20px;
      margin-bottom:20px;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="container">
    <p class="nro-top font-weight-bold"><strong class="colegiatura ">26270</strong></p>
    <div class="content">
      <h2 class="dynamic dynamic-constancia"><strong>CONSTANCIA DE HABILITACIÓN PROFESIONAL</strong></h2>
      <p class="content-body">El que suscribe, Decano del Consejo Directivo Regional II Junín del Colegio de Psicólogos del Perú, hace constar que el(la) Psicólogo(a):</p>
      <h1 class="dynamic style_agremiado" id="nombre">NOMBRE DEL PSICÓLOGO(A)</h1>
      <div class="content-body">
          <p>Identificado(a) con <strong>DNI <span class="dynamic" id="dni">XXXXXXXX</span></strong> es miembro ordinario <strong>Activo</strong> de nuestra Orden Profesional, suscrito en el Registro Único Nacional de Colegiatura con el <strong>N.º <span class="dynamic colegiatura">XXXXX</span></strong>, que se encuentra <strong>HABILITADO(A)</strong> para el ejercicio de la profesión de Psicólogo(a) dentro del territorio nacional, de conformidad a los establecidos en la Ley N°23019, Ley de Creación del Colegio de Psicologos del Perú modificado por Decreto Ley N°37043, Estatuto y Reglamento.</p>
          <p>Este documento tiene validez de <strong>90 días hábiles</strong> desde la expedición del documento, así mismo, se hace constar que pertenece al Consejo Directivo Regional II Junín.</p>
          <p>La presente Constancia de Habilitación se expide a solicitud de/el interesado(a), para fines que considere conveniente.</p>
      </div>
    </div>
    <div class="signature">
      <p><i><strong>Huancayo, <span class="" id="fecha">xxx</span></strong></i></p>
    </div>
    <div class="detalle-justi">
      <p class="colegio-detalles"><strong><i>COLEGIO DE PSICÓLOGOS DEL PERÚ </br> CONSEJO DIRECTIVO REGIONAL II JUNÍN</i></strong>
    </div>
    <div  class="footer">
      <strong><span id="numero">339411</strong></span>
    </div>
    
  </div>

  <script>
    const date_actual = new Date();
    const opciones = { day: '2-digit', month: 'long', year: 'numeric' };
    let date_format = date_actual.toLocaleDateString('es-ES', opciones);
    date_format = date_format.replace(/ de ([a-z])/i, (match, letra) => ` de ${letra.toUpperCase()}`);

    // Ejemplo para cargar datos dinámicos
    const datos = {
      colegiatura: "<?php echo $cliente['codigo'] ?>", 
      nombre: "<?php echo $cliente['razon_social'] ?>",
      dni: "<?php echo $cliente['nrodoc'] ?>",
      fecha: date_format,
      numfolio: "<?php for($k=0;$k<count($detalle);$k++){if($detalle[$k]['codigo']==8){echo "";}}  ?>"
    };

    document.getElementById("nombre").textContent = datos.nombre;
    document.getElementById("dni").textContent = datos.dni;
    for (let el of document.getElementsByClassName("colegiatura")) {
        el.textContent = datos.colegiatura;
    }
    document.getElementById("fecha").textContent = datos.fecha;
    
    document.getElementById("numero").textContent = datos.numfolio;
  </script>
</body>
</html>
