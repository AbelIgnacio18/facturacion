<?php
session_start();

require_once('../ApiFacturacion/model/clsCaja.php');

$objCaja = new clsCaja();
$id = $_GET['id'];
$item = 1;
$usuario = $_SESSION['usuario'];
$resumen = $objCaja->consultarReporteCaja($id,$usuario);
$resumen = $resumen->fetchAll(PDO::FETCH_ASSOC);

$caja = $objCaja->consultarCajaID($id);
$caja = $caja->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja - Colegio de Psicólogos Junín</title>
    <style>
        @page {
          size: A4;
          margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 15px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .section {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        .space{ white-space: nowrap}
        th {
            background-color: #f2f2f2;
        }
        .logo {
            display: block;
            margin: 0 auto;
            max-height: 100px;
        }
    </style>
</head>
<body onload="window.print()">
    <img src="../ApiFacturacion/logo_empresa2.jpg" alt="Logo Colegio Psicólogos Junín" class="logo">
    <h1>Colegio de Psicólogos Junín</h1>
    <h2>Reporte - Cierre de Caja</h2>

    <div class="section">
        <strong>Caja ID:</strong> <?php echo $caja['id']?><br>
        <strong>Usuario:</strong> <?php echo $_SESSION['usuario']?><br>
        <strong>Serie:</strong> <?php echo $caja['usuario']?><br>
        <strong>Apertura:</strong> <?php echo date('d/m/Y H:i', strtotime($caja['fechaapertura']))?><br>
        <strong>Cierre:</strong> <?php echo date('d/m/Y H:i', strtotime($caja['fechacierre']))?>
    </div>

    <div class="section">
        <h3>Totales</h3>
        <table>
            <tr>
                <th>Fondo Inicial</th>
                <td>S/. <?php echo $caja['inicial']?></td>
            </tr>
            <tr>
                <th>Sistema Efectivo</th>
                <td>S/. <?php echo $caja['efectivo']?></td>
            </tr>
            <tr>
                <th>Sistema Dep/Transfer</th>
                <td>S/. <?php echo $caja['transferencia']?></td>
            </tr>
            <tr>
                <th>Gastos</th>
                <td>S/. <?php echo $caja['gasto']?></td>
            </tr>
            <tr>
                <th>Total Cajero</th>
                <td>S/. <?php echo number_format($caja['inicial'] + $caja['efectivo'] + $caja['transferencia'] - $caja['gasto'], 2);
 ?></td>
            </tr>
            <tr>
                <th>Sobra/Falta</th>
                <td>Arqueo Exacto</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Resumen de Pagos del Día</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serie</th>
                    <th>Fecha</th>
                    <th>Método de Pago</th>
                    <th>Registro</th>
                    <th>Agremiado</th>
                    <th>V. Total</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumen as $value): ?>
                <tr>
                    <td><?php echo $item++ ?></td>
                    <td class="space"><?php echo $value['serie'] . '-' . $value['correlativo'] ?></td>
                    <td class="space"><?php echo $value['fecha_emision']?></td>
                    <td><?php echo $value['tipo_operacion']?></td>
                    <td><?php echo $value['codigo']?></td>
                    <td><?php echo $value['razon_social']?></td>
                    <td><?php echo $value['total']?></td>
                    <td><?php echo $value['descripcion_detalle']?></td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</body>
</html>
