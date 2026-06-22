<?php 
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);


    require_once("../model/clsCliente.php");
    require_once("../model/clsCaja.php");
    require_once("../model/clsProducto.php");
    require_once("../model/clsCompartido.php");
    require_once("../model/clsEmisor.php");
    require_once("../model/clsNotaCredito.php");
    require_once("../model/clsNotaDebito.php");
    require_once("../model/clsVenta.php");

    require_once("../api/api_genera_xml.php");
    require_once("../api/api_facturacion.php");
    require_once("../cantidad_en_letras.php");

    $accion = $_POST['accion'];
    operaciones($accion);//ejeciuta la funcion operaciones

    function operaciones($accion){
        $objEmisor = new clsEmisor();
        $objCompartido = new clsCompartido();
        $objCliente = new clsCliente();
        $objCaja = new clsCaja();
        $objProducto = new clsProducto();
        $objVenta = new clsVenta();
        $objNC = new clsNotaCredito();
        $objND = new clsNotaDebito();
        $api = new api_facturacion();
        $generadorXML = new api_genera_xml();
    
        switch ($accion) {
            case 'LISTAR_EMISORES':
                $listado = $objEmisor->consultarListaEmisores();
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
                break;

            case 'LISTAR_MONEDAS':
                $listado = $objCompartido->listarMonedas();
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;

            case 'LISTAR_COMPROBANTES':
                $listado = $objCompartido->listarComprobantesCodigo($_POST['tipo']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;

             case 'LISTAR_DOCUMENTOS':
                $listado = $objCompartido->listarTipoDocumentoCodigo($_POST['tipo']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;   

             case 'LISTAR_DOCUMENTOS_TODOS':
                $listado = $objCompartido->listarTipoDocumento();
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;   

            case 'LISTAR_SERIES':
                $listado = $objCompartido->listarSerie($_POST['tipocomp']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;  

            case 'BUSCAR_PRODUCTO':
                $listado = $objCompartido->listarProducto($_POST['filtro']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);

                $listado = array(
                    'listado'   =>  $listado
                );

                echo json_encode($listado);
                break;

             case 'OBTENER_CORRELATIVO':
                $listado = $objCompartido->obtenerSerie($_POST['idserie']);
                $listado = $listado->fetch(PDO::FETCH_NAMED);
                $correlativo = $listado['correlativo'];
                
                echo $correlativo;
                
                break;  
                
            case 'CONSULTA_DNI':
                $dni = $_POST['dni'];
                $url_ws = "https://api.apis.net.pe/v1/dni?numero=$dni";
                $header = array();

                    // create curl resource
                $ch = curl_init();           

                // set url del ws de sunat            
                curl_setopt($ch, CURLOPT_URL, $url_ws);

                //return the transfer as a string
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                // $output contains the output string
                $output = curl_exec($ch);
                curl_close($ch);
                echo $output;
                break;

            case 'CONSULTA_RUC':
            $ruc = $_POST['ruc'];
            $url_ws = "https://api.apis.net.pe/v1/ruc?numero=$ruc";
            $header = array();
            
                // create curl resource
            $ch = curl_init();           

            // set url del ws de sunat            
            curl_setopt($ch, CURLOPT_URL, $url_ws);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            // $output contains the output string
            $output = curl_exec($ch);
            curl_close($ch);
            echo $output;
            break;

            case 'ADD_PRODUCTO':
            //INICIO DEL CARRITO
            $producto = $objCompartido->obtenerProducto($_POST['codigo']);
            $producto = $producto->fetch(PDO::FETCH_NAMED);

            $cantidad_agregar = 1;

            if(isset($_POST['cantidad'])){
                $cantidad_agregar = $_POST['cantidad'];
            }

            $precio = $producto['precio'];
            if (isset($_POST['precio'])) {
                $precio = $_POST['precio'];
            }

            session_start();
            // si existe la sesion se añade un nuevo producto sino se crea una sesion llamada carrito como un array vacio
            if(!isset($_SESSION['carrito'])){
                $_SESSION['carrito'] = array();
            }

            $carrito = $_SESSION['carrito'];

            $item = count($carrito)+1;

            $existe = false;

            foreach($carrito as $key => $value){
            //Si existe el producto solo se aumenta la cantidad
                if($value['codigo'] == $_POST['codigo']){
                    $item = $key;
                    $existe = true;
                    break;
                }
            } 

            if($existe){
                $carrito[$item]['cantidad'] = $carrito[$item]['cantidad'] + $cantidad_agregar;
            }else{
                $carrito[$item] = array(
                    'codigo'                =>  $producto['codigo'],
                    'nombre'                =>  $producto['nombre'],
                    'precio'                =>  $precio, //$producto['precio'],
                    'unidad'                =>  $producto['unidad'],
                    'codigoafectacion'      =>  $producto['codigoafectacion'],
                    'cantidad'              =>  $cantidad_agregar,
                    'lote'                  =>  $producto['lote']
                    
                );
            }

            $_SESSION['carrito'] = $carrito;

            //Fin del carrito de compras...

            //Incializar vasriables
            $op_gravadas = 0.00;
            $op_exonerdas = 0.00;
            $op_inafectas = 0.00;
            $igv = 0.0;
            $igv_porcentaje = 0.18;
            $total = 0.00;

            foreach ($carrito as $key => $value) {
                
                if ($value['codigoafectacion'] == 10) { //GRAVADO
                    $op_gravadas += round(($value['precio'] * $value['cantidad']),2);
                }

                if ($value['codigoafectacion'] == 20) { //EXONERADO
                    $op_exonerdas += $value['precio'] * $value['cantidad'];
                }

                if ($value['codigoafectacion'] == 30) { //INAFECTO
                    $op_inafectas += $value['precio'] * $value['cantidad'];
                }

            }

            $igv = round(($op_gravadas * $igv_porcentaje),2);
            $total =$op_gravadas + $op_exonerdas + $op_inafectas + $igv;


            $html = "<table class='table table-hover table-sm'>
                        <tr>
                            <th>ITEM</th>
                            <th>CANT</th>
                            <th>UNID</th>
                            <th>PRODUCTO</th>
                            <th>V.U.</th>
                            <th>SUBTOTAL</th>
                        </tr>";

            $det_html = "";
            $desc_detalle ="";
            foreach ($carrito as $key => $value) {
                if($value['codigo']==7){
                    $cliente_vigencia = $objCliente->consultarCliente($_POST['cliente']);
                    $cliente_vigencia = $cliente_vigencia->fetch(PDO::FETCH_NAMED);
                    date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                    $fecha1 = strtotime($cliente_vigencia['fecha_pago']);//Fecha Pago de la BD PFBD
                    $num = $value['cantidad'];//CantMP Cantidad de Meses Pagados
                    $fecha_temp = date('Y-m-d',strtotime(date("Y-m-d",$fecha1). ' + '.$num.' month'));//Fecha Nueva de Pago
                    $fecha2 = strtotime($fecha_temp);
                    $fecha_temp2  = date('Y-m-d',strtotime(date("Y-m-d",$fecha2). ' + 3 month'));
                    
                    //Calculo de la Posicion del Mes Inicial
                    $dia_fecha_pago = date("d",$fecha1);
                    //echo var_dump(intval($dia_fecha_pago));
                    if(intval($dia_fecha_pago) < 5){
                        $Posicion_Mes_Inicial = date('Y-m-d',strtotime(date("Y-m-d",$fecha1)));//Posicion_Mes_Inicial
                        
                    }else{
                        $Posicion_Mes_Inicial = date('Y-m-d',strtotime(date("Y-m-d",$fecha1). ' + 5 days'));//Posicion_Mes_Inicial
                        
                    }
                    $fecha_Mes_Inicial = strtotime($Posicion_Mes_Inicial);
                    $Posicion_Mes_Inicial = strftime('%B',$fecha_Mes_Inicial )." del ".strftime('%Y',$fecha_Mes_Inicial );
                    
                    //Calculo de la Posicion del Mes Final
                    $Posicion_Mes_Final = date('Y-m-d',strtotime(date("Y-m-d",$fecha_Mes_Inicial). ' + '.($num-1).'month'));
                    $fecha_Mes_Final = strtotime($Posicion_Mes_Final);
                    $Posicion_Mes_Final = strftime('%B',$fecha_Mes_Final)." del ".strftime('%Y',$fecha_Mes_Final);
                    
                    //Calculo del mes de vigencia:
                    $Posicion_Mes_Vigencia = date('Y-m-d',strtotime(date("Y-m-d",$fecha_Mes_Final). ' + 3 month'));
                    $fecha_Mes_Vigencia = strtotime($Posicion_Mes_Vigencia);
                    $Posicion_Mes_Vigencia = strftime('%B',$fecha_Mes_Vigencia)." del ".strftime('%Y',$fecha_Mes_Vigencia);
                    
                    //$mes_pinicial=strftime('%B', $fecha1);
                    //$mes_pgracia=strftime('%B', $fecha2);
                    //$fecha_vigencia = strftime('%d/%m/%Y', strtotime($fecha_temp2));
                    
                    $periodo_pago = "Pago de ".$Posicion_Mes_Inicial." hasta ".$Posicion_Mes_Final;
                    $vigencia = "</br>Nueva Vigencia: ".$Posicion_Mes_Vigencia;
                    if($num == 1 ){
                        $periodo_pago = "Pago de ".$Posicion_Mes_Inicial;
                    }
                    $serie_folio = "";
                    $periodo_pago2 = "(".$periodo_pago.")";
                    $vigencia2 = "/".substr($vigencia,5,50).")";
                    $serie_folio2 = "";
                }elseif(($value['codigo']==6) || ($value['codigo']==8)){
                     if($value['cantidad']==1){
                     $comentario_folio="";    
                     }else{
                     $comentario_folio=" al ".($value['lote']+$value['cantidad']-1);    
                     }
                     $serie_folio = "Folio:".($value['lote']).$comentario_folio;
                     $periodo_pago = "";
                     $vigencia = "";
                     $periodo_pago2 = "";
                     $vigencia2 = "";
                     $serie_folio2 = "(".$serie_folio.")";
                }else{
                     $serie_folio = "";
                     $periodo_pago = "";
                     $vigencia = "";
                     $serie_folio2 = "";
                     $periodo_pago2 = "";
                     $vigencia2 = "";
                }
                
                //Aqui enviaremos el detalle de venta a un input del carrito de compras
                $detalle_producto = $value['nombre'].$periodo_pago2 . $vigencia2 . $serie_folio2;
                $desc_detalle = $desc_detalle.$value['cantidad']." X ".$detalle_producto." a S/.".$value['precio']."=> S/.".$value['cantidad']*$value['precio']."</br>";
                $det_html = $det_html . "<tr>
                                <td>" . $key . "</td>
                                <td>" . $value['cantidad'] . "</td>
                                <td>" . $value['unidad'] . "</td>
                                <td>".  $value['nombre']. "<p class='my-0'>$serie_folio</p><p class='my-0'>$periodo_pago $vigencia</p>
                                <input class='d-none' type='text' value='$periodo_pago$vigencia$serie_folio' id='detalle_producto$key' name='detalle_producto$key'></td>
                                <td>" . round($value['precio'],2) . "</td>
                                <td>" . round(($value['cantidad'] * $value['precio']),2) . "</td>
                            </tr>";
            }

            $html = $html . $det_html;
            $html = $html . "<tr><td colspan='5' align='right'>OP. GRAVADAS</td><td>" . $op_gravadas . "</td></tr>";
            $html = $html . "<tr><td colspan='5' align='right'>OP. EXONERADAS</td><td>" . $op_exonerdas . "</td></tr>";
            $html = $html . "<tr><td colspan='5' align='right'>OP. INAFECTAS</td><td>" . $op_inafectas . "</td></tr>";
            $html = $html . "<tr><td colspan='5' align='right'>IGV</td><td>" . $igv . "</td></tr>";
            $html = $html . "<tr><td colspan='5' align='right'><b>TOTAL</b></td><td>" . $total . "</td></tr>";
            $html = $html . "</table>";
            $html = $html . "<input class='d-none' type='text' name='descripcion_detalle' id='descripcion_detalle'  value='$desc_detalle' >";
            echo $html;

            break;

            case 'GUARDAR_VENTA':
                //datos del emisor
                $idemisor = $_POST['idemisor'];
                $emisor = $objEmisor->obtenerEmisor($idemisor);
                $emisor = $emisor->fetch(PDO::FETCH_NAMED);

                //datos del cliente
                $cliente = array(
                    'tipodoc'           =>  $_POST['tipodoc'],//1 DNI 6 es RUC
                    'nrodoc'            =>  $_POST['nrodoc'],
                    'razon_social'      =>  $_POST['razon_social'],
                    'direccion'         =>  $_POST['direccion'],
                    'pais'              =>  'PE'
                );
                
                $cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);
                if ($cliente_existe->rowCount()>0) {
                    
                    $cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
                }else{
                    $objCliente->insertarClienteBoleta($cliente);
                    $cliente_existe = $objCliente->consultarCliente($_POST['nrodoc']);
                    $cliente_existe = $cliente_existe->fetch(PDO::FETCH_NAMED);
                }

                $idCliente = $cliente_existe['id'];

                //datos del carrito
                session_start();
                $carrito = $_SESSION['carrito'];
                $detalle = array();
                $igv_porcentaje = 0.18;
                $op_gravadas = 0.00;
                $op_exoneradas = 0.00;
                $op_inafectas = 0.00;
                $igv = 0.00;
                $total_impuesto_bolsas = 0.00;
                $op_gratuitas_1 = 0.00;
                $op_gratuitas_2 = 0.00;

                foreach ($carrito as $key => $value) {
                $producto = $objCompartido->obtenerProducto($value['codigo']);
                //Obtener codigo de producto si value codigo es igual 7 entonces agregar fecha de vigencia
                
                $producto = $producto->fetch(PDO::FETCH_NAMED);

                $afectacion = $objCompartido->obtenerRegistroAfectacion($producto['codigoafectacion']);
                $afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

                $igv_detalle = 0;
                $factor_porcentaje = 1;

                if ($producto['codigoafectacion'] == 10 || $producto['codigoafectacion'] == 12) {
                    $igv_detalle = round(($value['precio'] * $value['cantidad'] * $igv_porcentaje),2);
                    $factor_porcentaje = 1 + $igv_porcentaje;
                }
                
                //Actualizar Folios productos
                
                if($producto['lote'] != 0){
                        $lote_stock = $producto['lote']+$value['cantidad'];
                        $desc_folio = $producto['stock']-$value['cantidad'];
                        $producto2 = array(
                            'codigofolio'  =>  $value['codigo'],
                            'lotefolio'  =>  $lote_stock,
                            'descfolio' =>  $desc_folio
                        );
        
                        $objProducto->actualizarFolio($producto2);        
                }
                
                //Actualizar Status Cliente
                
                if($value['codigo'] == 7){
                    $cliente_vigencia2 = $objCliente->consultarCliente($_POST['nrodoc']);
                    $cliente_vigencia2 = $cliente_vigencia2->fetch(PDO::FETCH_NAMED);
                    date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                    $hoy = date("Y-m-d");
                    $fecha1 = strtotime($cliente_vigencia2['fecha_pago']);
                    $num = $value['cantidad'];
                    $fecha_temp = date('Y-m-d',strtotime(date("Y-m-d",$fecha1). ' + '.$num.' month'));
                    $fecha2 = strtotime($fecha_temp);
                    $fecha_temp2  = date('Y-m-d',strtotime(date("Y-m-d",$fecha2). ' + 89 days'));
                    
                    if($fecha_temp2 > $hoy){//Si la vigencia esta despues de la fecha actual entonces estas quiere decir que falta tiempo para que vence el plazo y sigues habilitado 
                        $status = "HABILITADO";
                    }else{
                        $status = "INHABILITADO";
                    }
                    //$fecha_vigencia = strftime('%Y-%m-%d', strtotime($fecha_temp2));
                    $cliente2 = array(
                            'idcliente'  =>  $idCliente,
                            'fechapago'  =>  $fecha_temp,
                            'fechavigencia'  =>  $fecha_temp2,
                            'status'        =>  $status
                        );
                    $objCliente->actualizarStatus($cliente2);
                }
                
                
                $item_producto = array(
                    'item'                              => $key,
                    'codigo'                            => $value['codigo'],
                    'descripcion'                       =>  $value['nombre'],
                    'detalle_producto'                  =>  $_POST['detalle_producto'.$key],
                    'cantidad'                          =>  $value['cantidad'],
                    'valor_unitario'                    =>  $value['precio'], //no incluye impuestos
                    'precio_unitario'                   =>  $value['precio'] * $factor_porcentaje, //si incluye impuestos
                    'tipo_precio'                       =>  $producto['tipo_precio'], //01 lucra, 02: no lucra
                    'igv'                               =>  $igv_detalle,
                    'porcentaje_igv'                    =>  $igv_porcentaje * 100,
                    'valor_total'                       =>  round(($value['precio'] * $value['cantidad']),2),
                    'importe_total'                     =>  $value['precio'] * $value['cantidad'] * $factor_porcentaje,
                    'unidad'                            =>  $value['unidad'],
                    'tipo_afectacion_igv'               =>  $producto['codigoafectacion'], //GRAVADO: 10, EXONERADO: 20, INAFECTO: 30
                    'codigo_tipo_tributo'               =>  $afectacion['codigo_afectacion'],
                    'tipo_tributo'                      =>  $afectacion['tipo_afectacion'],
                    'nombre_tributo'                    =>  $afectacion['nombre_afectacion'],
                    'bolsa_plastica'                    =>  'NO',
                    'total_impuesto_bolsas'             =>  0.00

                );

                $detalle[] = $item_producto;

                if ($item_producto['tipo_afectacion_igv'] == 10) {
                    $op_gravadas = $op_gravadas + $item_producto['valor_total'];
                }

                if ($item_producto['tipo_afectacion_igv'] == 20) {
                    $op_exoneradas = $op_exoneradas + $item_producto['valor_total'];
                }

                if ($item_producto['tipo_afectacion_igv'] == 30) {
                    $op_inafectas = $op_inafectas + $item_producto['valor_total'];
                }

                $igv = $igv + $igv_detalle;

                $total_impuesto_bolsas = $total_impuesto_bolsas + $item_producto['total_impuesto_bolsas'];
                }

                $total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $total_impuesto_bolsas;

                //Actualizar Caja Totales :

                date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                $hoy = date("Y-m-d");
                if($_POST['fecha_emision']==$hoy){
                    $caja = $objCaja->consultarAperturaCaja($hoy,$_POST['userboleta']);
                    $caja = $caja->fetch(PDO::FETCH_NAMED);
                    
                    $acumulado_efectivo = $caja['efectivo'];
                    $acumulado_transferencia = $caja['transferencia'];
                    if($_POST['tipo_pago'] == "Efectivo"){
                        $acumulado_efectivo = $acumulado_efectivo +  $total;
                    }
                    if($_POST['tipo_pago'] == "Transferencia"){
                        $acumulado_transferencia = $acumulado_transferencia +  $total;
                    }
                
                    $venta_acumulada = array(
                            'idCaja'  =>  $caja['id'],
                            'efectivo'  =>  $acumulado_efectivo,
                            'transferencia'  =>  $acumulado_transferencia
                        );
                    $objCaja->actualizarCaja2($venta_acumulada);
                }

                //datos del comprobante: serie, forma de pago

                $idserie = $_POST['idserie'];
                $seriex = $objCompartido->obtenerSerie($idserie);
                $seriex = $seriex->fetch(PDO::FETCH_NAMED);
                
                //correlativo con formato de ceros a la izquierda 00000001
                
                $correlativo_sunat = (string)(100000000 + $seriex['correlativo']);
                $correlativo_sunat = substr($correlativo_sunat,1,8);

                $monto_pendiente = 0.00;

                if($_POST['forma_pago'] == 'Credito'){
                    $monto_pendiente = $_POST['monto_pendiente'];
                }

                $comprobante = array(
                'tipodoc'                           =>  $_POST['tipocomp'],
                'idserie'                           =>  $idserie,
                'serie'                             =>  $seriex['serie'],
                'correlativo'                       =>  $correlativo_sunat,
              //'correlativo'                       =>  $seriex['correlativo'],
                'fecha_emision'                     =>  $_POST['fecha_emision'],
                'hora'                              =>  '00:00:00',
                'fecha_vencimiento'                 =>  $_POST['fecha_emision'],
                'moneda'                            =>  $_POST['moneda'],
                'total_opgravadas'                  =>  $op_gravadas,
                'total_opexoneradas'                =>  $op_exoneradas,
                'total_opinafectas'                 =>  $op_inafectas,
                'total_opgratuitas_1'               =>  $op_gratuitas_1,
                'total_opgratuitas_2'               =>  $op_gratuitas_2,
                'total_impbolsas'                   =>  $total_impuesto_bolsas,
                'igv'                               =>  $igv,
                'total'                             =>  $total,
                'forma_pago'                        =>  $_POST['forma_pago'],
                'monto_pendiente'                   =>  $monto_pendiente,
                'codcliente'                        =>  $idCliente,
                'total_texto'                       =>  CantidadEnLetra($total),
                'tipo_pago'                         =>  $_POST['tipo_pago'],
                'nro_operacion'                     =>  isset($_POST['nro_operacion']) ? $_POST['nro_operacion'] : '',
                'fecha_operacion'                   =>  isset($_POST['fecha_operacion2']) ? $_POST['fecha_operacion2'] : '0000-00-00',
                'descripcion_detalle'               =>  $_POST['descripcion_detalle'],
                'usuario'                           =>  $_POST['userboleta']
                );

                if ($_POST['forma_pago'] == 'Credito') {
                    $nrocuotas = $_POST['cuotas'];
                    $cuotas = array();
                    for ($i=1; $i <= $nrocuotas ; $i++) { 
                        $cuotas[] = array(
                            'cuota'         =>  'Cuota' . str_pad($i, 3, "0", STR_PAD_LEFT),
                            'monto'         =>  $_POST['txtMonto' . $i],
                            'fecha'         =>  $_POST['txtFecha' . $i],
                        );
                    }
                }else{
                    $cuotas = null;
                }

                if( $total<=700 || !empty($_POST['direccion']) ){
                    //insertar en la base de datos
                    $objCompartido->actualizarSerie($idserie, $comprobante['correlativo']);
                    $objVenta->insertarVenta($idemisor, $comprobante);
                    $venta = $objVenta->obtenerUltimoComprobanteId();
                    $venta = $venta->fetch(PDO::FETCH_NAMED);
                    $objVenta->insertarDetalle($venta['id'], $detalle);
                    //INICIA FACTURACIÓN ELECTRONICA
                    //1.- CREAR EL XML
                    $api_xml = new api_genera_xml();
                    $nombreXML = $emisor['nrodoc'] . '-' . $comprobante['tipodoc']  . '-' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
                    $rutaXML = '../xml/';
                    
                    $api_xml -> crea_xml_invoice($rutaXML . $nombreXML, $emisor,$cliente,$comprobante,$detalle,$cuotas);
                    //2.- ENVIAR O CONSUMIR LA WS DE SUNAT
    
                    $objSUNAT = new api_facturacion();
                    $estado_envio = $objSUNAT->enviar_comprobante($emisor,$nombreXML,"../certificado_digital/","../xml/","../cdr/");
                    //actualizar estado de venta
                    $objVenta->actualiza_envio_fe($venta['id'],$estado_envio);
                    if ($item_producto['codigo'] == 8) {
                    $html_constancia = '<a style="font-weight:bold;font-style:italic" href="#" 
                            onclick="imprimirConstancia(' . $venta['id'] . '); return false;">
                            Imprimir Constancia de Habilitación
                        </a>';
                    }else{
                    $html_constancia='';    
                    }
                    //echo var_dump($estado_envio);
                    if($estado_envio['estado']==8){
                        echo '<div class="card-header bg-success alert alert-success" role="alert" id="' . $venta['id'] . '">
                                    BOLETA ELECTRONICA ('.$comprobante['serie'].' - '.$comprobante['correlativo'].' ) aceptado por Sunat!!!'.$html_constancia.'
                                </div>';
                    }else{
                        echo '<div class="card-header bg-danger alert alert-danger" role="alert">
                                    BOLETA ELECTRONICA ('.$comprobante['serie'].' - '.$comprobante['correlativo'].' ) tiene Observaciones!!!
                                </div>';
                    }
                    unset($_SESSION['carrito']);
                    //session_destroy();
                    
                    //TERMINAFACTURACIÓN ELECTRONICA 
    
                    //IMPRIMIR O GENERAR EL PDF EN PANTALLA
                        echo "<script>var ventana = window.open('./ApiFacturacion/pdf_prueba.php?id=" . $venta['id'] . "','_blank');
                        setTimeout(function() {
                        ventana.close();  // Cierra la ventana después de un breve retraso
                        }, 2500);</script>";
                    
                }else{
                    echo "<div class='alert alert-danger' role='alert'>Su vente supero los 700 soles, requiere Actualizar la Direccion</div>"; 
                }
                //evniar por email
            
            break;
            

            case "CANCELAR_CARRITO":

            session_start();
            unset($_SESSION['carrito']);

            break;
            
            case 'GUARDAR_NC':
            
            //datos del emisor
            
            $id = $_POST['idboleta'];
            $venta = $objVenta->obtenerComprobanteId($id);
            $venta = $venta->fetch(PDO::FETCH_NAMED);
                
            $detalle_venta = $objVenta->listarDetalleComprobanteId($id);
            $detalle_venta = $detalle_venta->fetchAll(PDO::FETCH_NAMED);
            
            $idemisor = $venta['idemisor'];
            $emisor = $objEmisor->obtenerEmisor($idemisor);
            $emisor = $emisor->fetch(PDO::FETCH_NAMED);
                
            $tipo_comprobante = $objCompartido->obtenerComprobante($venta['tipocomp']);
            $tipo_comprobante = $tipo_comprobante->fetch(PDO::FETCH_NAMED);
                
            $cliente = $objCliente->consultarClientePorCodigo($venta['codcliente']);
            $cliente = $cliente->fetch(PDO::FETCH_NAMED);
            $idcliente = $cliente['id'];
            
            //obtener detalle de venta
            
            $detalle = array();
            $igv_porcentaje = 0.18;
            $op_gravadas = 0.00;
            $op_exoneradas = 0.00;
            $op_inafectas = 0.00;
            $igv = 0.00;

            foreach ($detalle_venta as $key => $value) {
                $producto = $objCompartido->obtenerProducto($value['codigo']);
                $producto = $producto->fetch(PDO::FETCH_NAMED);

                $afectacion = $objCompartido->obtenerRegistroAfectacion($producto['codigoafectacion']);
                $afectacion = $afectacion->fetch(PDO::FETCH_NAMED);

                $igv_detalle = 0;
                $factor_porcentaje = 1;

                if ($producto['codigoafectacion'] == 10) {
                    $igv_detalle = $value['precio'] * $value['cantidad'] * $igv_porcentaje;
                    $factor_porcentaje = 1 + $igv_porcentaje;
                }

                $item_producto = array(
                    'item'                              =>  $key+1, //correlativo iniciando desde 1
                    'codigo'                            =>  $value['codigo'], //codigo del producto/servicio
                    'descripcion'                       =>  $value['nombre'],
                    'cantidad'                          =>  $value['cantidad'],
                    'valor_unitario'                    =>  $producto['precio'], //no incluye impuestos
                    'precio_unitario'                   =>  $producto['precio'] * $factor_porcentaje, //si incluye impuestos
                    'tipo_precio'                       =>  $producto['tipo_precio'], //Catálogo No. 16: Códigos – Tipo de precio de venta unitario
                    'igv'                               =>  $igv_detalle,
                    'porcentaje_igv'                    =>  $igv_porcentaje * 100,
                    'valor_total'                       =>  $producto['precio'] * $value['cantidad'], //Cantidad * valor unitario
                    'importe_total'                     =>  $producto['precio'] * $value['cantidad'] * $factor_porcentaje, //Cantidad * precio unitario
                    'unidad'                            =>  $producto['unidad'],
                    'tipo_afectacion_igv'               =>  $producto['codigoafectacion'], //GRAVADO: 10, EXONERADO: 20, INAFECTO: 30, Catálogo No. 07: Códigos de tipo de afectación del IGV
                    'codigo_tipo_tributo'               =>  $afectacion['codigo_afectacion'], //Catálogo No. 05: Códigos de tipos de tributos
                    'tipo_tributo'                      =>  $afectacion['tipo_afectacion'],
                    'nombre_tributo'                    =>  $afectacion['nombre_afectacion'],
                    'bolsa_plastica'                    =>  'NO'
                );

                $detalle[] = $item_producto;

                if ($item_producto['tipo_afectacion_igv'] == 10) {
                    $op_gravadas = $op_gravadas + $item_producto['valor_total'];
                }

                if ($item_producto['tipo_afectacion_igv'] == 20) {
                    $op_exoneradas = $op_exoneradas + $item_producto['valor_total'];
                }

                if ($item_producto['tipo_afectacion_igv'] == 30) {
                    $op_inafectas = $op_inafectas + $item_producto['valor_total'];
                }

                $igv = $igv + $igv_detalle;
            }
            
            $total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;

            $idserie = $_POST['idserie']; //Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
            $seriex = $objCompartido->obtenerSerie($idserie);
            $seriex = $seriex->fetch(PDO::FETCH_NAMED);

            $motivo = $objCompartido->getRegistroTablaParametrica('C', $_POST['motivo']);// Campo obligatorio que vamos a tomar dejar el input
            $motivo = $motivo->fetch(PDO::FETCH_NAMED);
            
            //correlativo con formato de ceros a la izquierda 00000001
                
            $correlativo_nota = (string)(100000000 + $seriex['correlativo']);
            $correlativo_nota = substr($correlativo_nota,1,8);
            
            $correlativo_referencia = (string)(100000000 + $_POST['correlativo_ref2']);
            $correlativo_referencia = substr($correlativo_referencia,1,8);
            
            
            
            $comprobante = array(
                'tipodoc'                   =>  $_POST['tipocomp'], //Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'idserie'                   =>  $idserie,
                'serie'                     =>  $seriex['serie'],
                'correlativo'               =>  $correlativo_nota,
              //'correlativo'               =>  $seriex['correlativo'],
                'fecha_emision'             =>  $_POST['fecha_emision'],//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'hora'                      =>  '00:00:00',
                'fecha_vencimiento'         =>  $_POST['fecha_emision'],//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'moneda'                    =>  $_POST['moneda'], //SOLES=PEN, DOLARES=USD ,//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'total_opgravadas'          =>  $op_gravadas,
                'total_opexoneradas'        =>  $op_exoneradas,
                'total_opinafectas'         =>  $op_inafectas,
                'total_impbolsas'           =>  0.00,
                'igv'                       =>  $igv,
                'total'                     =>  $total,
                'total_texto'               =>  CantidadEnLetra($total),
                'codcliente'                =>  $idcliente,
                'tipodoc_ref'               =>  $_POST['tipocomp_ref'],//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'serie_ref'                 =>  $venta['serie'],//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'correlativo_ref'           =>  $correlativo_referencia,//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'codmotivo'                 =>  $_POST['motivo'],//Dejaremos este input que nos da de manera automatica debemos dejarlo en la vista tipo hidden
                'descripcion'               =>  $motivo['descripcion'],
                'pais'                      =>  'PE'

            );
            
            //DESCONTAR TOTALES EN VENTA 
            
            
            
            //INSERTAMOS LA NOTA DE CREDITO EN BASE DE DATOS
            $objCompartido->actualizarSerie($idserie, $comprobante['correlativo']);
            $objNC->insertarNotaCredito($idemisor, $comprobante);
            $objVenta->actualizarVenta($comprobante);
            $nc = $objNC->obtenerUltimoComprobanteId();
            $nc = $nc->fetch(PDO::FETCH_NAMED);
            $objNC->insertarDetalleNotaCredito($nc['id'], $detalle);

            //ENVIO DE COMPROBANTE A SUNAT
            //1. XML
            $nombre = $emisor['nrodoc'] . '-' . $comprobante['tipodoc'] . '-' . $comprobante['serie']  . '-' .  $comprobante['correlativo'];
            $ruta = '../xml/';

            $generadorXML->crea_xml_notacredito($ruta . $nombre, $emisor, $cliente, $comprobante, $detalle);

            //2. ENVIO A WS-SUNAT
            $estado_envio = $api->enviar_comprobante($emisor, $nombre, "../certificado_digital/", "../xml/", "../cdr/");
            //MENSAJE
                    if($estado_envio['estado']==8){
                        echo var_dump($estado_envio);
                        echo '<div class="alert alert-success" role="alert" >
                                    Comprobante aceptado por Sunat!!!
                                </div>';
                    }else{
                        echo var_dump($estado_envio);
                        echo '<div class="alert alert-danger" role="alert">
                                    El comprobante tiene Observaciones!!!
                                </div>';
                    }

            
            
            break;        
    
        
            //modulo de cliente
            case 'LISTAR_CLIENTE':
                date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                
                
                
                $listado = $objCliente->consultarListaCliente($_POST['status']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
            break;
            
            case 'ADD_CLIENTE':
                    
                    date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                    $hoy = date("Y-m-d");
                    $fecha2 = strtotime($_POST['fecha_pago']);
                    $fecha_temp2  = date('Y-m-d',strtotime(date("Y-m-d",$fecha2). ' + 89 days'));
                    
                    if($fecha_temp2 > $hoy){//Si la vigencia esta despues de la fecha actual entonces estas quiere decir que falta tiempo para que vence el plazo y sigues habilitado 
                        $status = "HABILITADO";
                    }else{
                        $status = "INHABILITADO";
                    }
                
                     $cliente = array(
                    'codigo'           => $_POST['codigo'],
                    'razon_social'  =>  $_POST['razon_social'],
                    'tipodoc'      =>  $_POST['tipodoc'],
                    'nrodoc'      =>  $_POST['nrodoc'],
                    'fecha_pago'=>  $_POST['fecha_pago'],
                    'fecha_vigencia' => $fecha_temp2,
                    'fechacolegiatura'        =>  $_POST['fechacolegiatura'],
                    'direccion'        =>  $_POST['direccion'],
                    'correo_electronico'        =>  $_POST['correo'],
                    'telefono'        =>  $_POST['telefono'],
                    'status'        => $status
                );
          
                
               $objCliente->insertarCliente($cliente);
              
            break; 
            
            
            
            case 'EDITAR_ID_AGREMIADO':
                    
              
               $listado =$objCliente->consultarClientePorId($_POST['id']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
              

            break;
            
            
             case 'ACTUALIZAR_AGREMIADOFF':
                  
                   $cliente = array(
                    'id'=>  $_POST['id'],  
                    'codigo'=>  $_POST['codigo'],//1 DNI 6 es RUC
                    'razon_social'=>  $_POST['razon_social'],
                    'tipodoc'=>  $_POST['tipodoc'],
                    'nrodoc'=>  $_POST['nrodoc'],
                    'fecha_pago'=>  $_POST['fecha_pago'],
                    'fechacolegiatura'=>  $_POST['fechacolegiatura'],
                    'direccion'=>  $_POST['direccion'],
                    'correo_electronico'=>  $_POST['correo'],
                     'telefono'=>  $_POST['telefono'],
                );
                
                  $objCliente->actualizarCliente($cliente);

            break;
            
            
             case 'ELIMINAR_AGREMIADO':
                 
              
               $objCliente->eliminarCliente($_POST['id']);
            break;
       
            
            //modulo e proucto
            case 'LISTAR_PRODUCTO3':
                
                 $listado = $objProducto->consultarListaProducto();
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
            break;
            
            
            case 'ADD_PRODUCTO4WW':
                  
                   $producto = array(
                    'nombre'           =>  $_POST['nombre'],//1 DNI 6 es RUC
                    'cuenta_contable'  =>  $_POST['nombre'],
                    'precio'      =>  $_POST['precio'],
                    'tipo_precio'      =>  $_POST['tipo_precio'],
                    'codigo_afectacion'=>  $_POST['codigo_afectacion'],
                    'unidad'        =>  $_POST['unidad'],
                    'lote'        =>  $_POST['lote'],
                    'stock'        =>  $_POST['stock'],
                );
          
                
               $objProducto->insertarProducto($producto);
            break;
            
            case 'ELIMINAR_PRODUCTO333':
                 
              
               $objProducto->eliminarProducto($_POST['codigo']);
            break;
            
             case 'EDITAR_CODIGO_PRODUCTO':
                 
              
               $listado =$objProducto->consultarProductoPorCodigo($_POST['codigo']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
            break;
            
            
            case 'EDITAR_CODIGO_PRODUCTO':
                 
              
               $listado =$objProducto->consultarProductoPorCodigo($_POST['codigo']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
            break;
            
             case 'ACTUALIZAR_PRODUCTO':
                 
               $producto = array(
                   'codigo'      =>  $_POST['codigo'],
                    'nombre'           =>  $_POST['nombre'],//1 DNI 6 es RUC
                    'precio'      =>  $_POST['precio'],
                    'tipo_precio'        =>  $_POST['tipo_precio'],
                    'codigoafectacion'        =>  $_POST['codigoafectacion'],
                    'unidad'        =>  $_POST['unidad'],
                    'lote'        =>  $_POST['lote'],
                    'stock'        =>  $_POST['stock'],
                );
               $objProducto->actualizarproducto($producto);
               
            break;
            //cajaa
        
            case 'CONSULTAR_APERTURA_CAJA':
                 date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                $hoy = date("Y-m-d");
                 $listado = $objCaja->consultarAperturaCaja($hoy,$_POST['usuario']);
                 $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                 
                 
                 $listado = array(
                    'listado' => $listado
                );

                 echo json_encode($listado);
                
                
            break;
            
            case 'LISTAR_CAJA':
                  
                 
                 $listado = $objCaja->consultarListaCaja($_POST['user']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
                
            break;
            //actualizar cajass
            case 'apertura_cajas_nuevo':
                  
                   $caja = array(
                    'referencia'=>  $_POST['referencia'],//1 DNI 6 es RUC
                    'usuario'  =>  $_POST['usuario'],
                    'fechaapertura'=>  $_POST['fechaapertura'],
                    'fondoinicial' =>  $_POST['fondoinicial'],
                    'estado' =>  "Aperturado",
                    'efectivo' => 0,
                    'transferencia' => 0
                  
                );
          
                
               $objCaja->insertarCaja($caja);
            break;
            
                  case 'actualizar_cajas_cierre':
                  
                   $cajacierre = array(
                    'referencia'=>  $_POST['referencia'],
                    'usuario'=>  $_POST['usuario'],
                    'fechacierre'=>  $_POST['fechacierre'],//1 DNI 6 es RUC
                    'efectivo'  =>  $_POST['efectivo'],
                    'transferencia'=>  $_POST['transferencia'],
                    'gasto' =>  $_POST['gasto'],
                    'estado' =>  "Cerrado",
                  
                );
      
                
               $objCaja->actualizarcaja($cajacierre);
            break;
            
            case 'OBTENER_COLEGIADO':
                  
                $listado = $objCliente->consultarColegiado($_POST['codigocol']);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $listado = array(
                    'listado' => $listado
                );

                echo json_encode($listado);
            break;
            
            case 'OBTENER_TEFECTIVO':
                date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                $hoy = date("Y-m-d");
                $venta = array(
                    'fecha_emision' => $hoy,
                    'tipo_operacion' => "efectivo",
                    'users' => $_POST["user"],
                );
                
                $listado = $objCaja->obtenerTotales($venta);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $total_efectivo = $listado[0]['total'];
                
                if($total_efectivo==null){
                    $total_efectivo=0;
                }
                
                echo $total_efectivo;
                break;
                
            case 'OBTENER_TTRANSFERENCIA':
                date_default_timezone_set("America/Lima");setlocale(LC_TIME, 'es_VE.UTF-8','esp');
                $hoy = date("Y-m-d");
                $venta = array(
                    'fecha_emision' => $hoy,
                    'tipo_operacion' => "transferencia",
                    'users' => $_POST["user"],
                );
                
                $listado = $objCaja->obtenerTotales($venta);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $total_transferencia = $listado[0]['total'];
                
                if($total_transferencia==null){
                    $total_transferencia=0;
                }
                
                echo $total_transferencia;
                break;
            
              
              
            case 'obteneraperturaandinicial':
                
            
                $listado = $objCaja-> obteneraperturaandinicial($_POST["user"]);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                echo json_encode($listado);
                break;
                
            case 'BUSCAR_BOLETA':
                $boleta = array(
                    'serie'=> $_POST['serie'],
                    'correlativo'=> $_POST['correlativo']
                    );
                $listado = $objVenta->MostrarBoleta($boleta);
                $listado = $listado->fetchAll(PDO::FETCH_NAMED);
                
                $id = $listado[0]['id'];
                
                
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
                
                $detalle_operacion = '';
                $cod_moneda = '';
                if($venta['tipo_operacion']=="Transferencia"){
                    $detalle_operacion = '<p>Número operación: '.$venta['numero_operacion']. '</p><p>Fecha operación: '.date("d/m/Y", strtotime($venta["fecha_operacion"])). '</p>';
                }
                if($venta['codmoneda']=="PEN"){
                    $cod_moneda ="Soles";
                }
                
                
                
                $listado = array(
                    'idboleta' => $id,
                    'empresa' => $emisor['razon_social'],
                    'ruc' => $emisor['nrodoc'],
                    'direccion_emisor' => $emisor['direccion'],
                    'tipoDocumento' => $tipo_comprobante['descripcion'],
                    'serieNumero' => $venta['serie'].'-'.$venta['correlativo'],
                    
                    //Cliente : 
                    'nombre' => $cliente['razon_social'],
                    'dni' => $cliente['nrodoc'],
                    'direccion_cliente' => $cliente['direccion'],
                    'codigo' =>$cliente['codigo'],
                    'fecha_emision' =>date("d/m/Y", strtotime($venta["fecha_emision"])),
                    'metodo_pago' =>$venta['tipo_operacion'],
                    'detalle_operacion' =>  $detalle_operacion,
                    'codmoneda' =>$cod_moneda,
                    
                    //Detalle
                    'productos' => $detalle,
                    
                    //Totales
                    'gravada' => $venta['op_gravadas'],
                    'exoneradas' => $venta['op_exoneradas'],
                    'inafectas' => $venta['op_inafectas'],
                    'igv' => $venta['igv'],
                    'total' => $venta['total'],
                    'total_letras' => CantidadEnLetra($venta['total']),
                    
                    
                    //Otros
                    'codigo_hash' => 'JMALCMdshrCfjecLOZKpJyKQ/Ek=',
                    'vendedor' => $venta['usuario'],
                    'urlConsulta' => 'https://e-consulta.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm'
                );
                
                echo json_encode($listado);
                
                break;
         
            
            default:
                # code...
                break;
        }
    }
    ?>
