<?php

require_once('conexion.php');

class clsVenta{

    function insertarVenta($idemisor, $venta){
        $sql = "INSERT INTO venta(id, idemisor, tipocomp, idserie, serie, correlativo, fecha_emision, codmoneda, op_gravadas, op_exoneradas, op_inafectas, igv, total, codcliente, forma_pago, monto_pendiente, numero_operacion, tipo_operacion,fecha_operacion,descripcion_detalle,usuario)
        VALUES (NULL, :idemisor, :tipocomp, :idserie, :serie, :correlativo, :fecha_emision, :codmoneda, :op_gravadas, :op_exoneradas, :op_inafectas, :igv, :total, :codcliente, :forma_pago, :monto_pendiente, :numero_operacion, :tipo_operacion,:fecha_operacion,:descripcion_detalle,:usuario)";

        $parametros = array(
            ':idemisor'=>$idemisor,
            ':tipocomp'=>$venta['tipodoc'],
            ':idserie' =>$venta['idserie'],
            ':serie'   =>$venta['serie'],
            ':correlativo' =>$venta['correlativo'],
            ':fecha_emision'=>$venta['fecha_emision'],
            ':codmoneda'  => $venta['moneda'],
            ':op_gravadas'=>$venta['total_opgravadas'],
            ':op_exoneradas'=>$venta['total_opexoneradas'],
            ':op_inafectas' =>$venta['total_opinafectas'],
            ':igv'			=>$venta['igv'],
            ':total'		=>$venta['total'],
            ':codcliente'	=>$venta['codcliente'],
            ':forma_pago'	=>$venta['forma_pago'],
            ':monto_pendiente'	=>	$venta['monto_pendiente'],
            ':numero_operacion' => $venta['nro_operacion'],
            ':tipo_operacion'   => $venta['tipo_pago'],
            ':fecha_operacion'   => $venta['fecha_operacion'],
            ':descripcion_detalle' => $venta['descripcion_detalle'],
            ':usuario'          => $venta['usuario']
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function insertarDetalle($idventa, $detalle){
        $sql = "INSERT INTO detalle(id,idventa, item, idproducto, cantidad, valor_unitario, precio_unitario, igv, porcentaje_igv, valor_total, importe_total, detalle_producto)
			VALUES (NULL, :idventa, :item, :idproducto, :cantidad, :valor_unitario, :precio_unitario, :igv, :porcentaje_igv, :valor_total, :importe_total, :detalle_producto)";
	
        global $cnx;
        $pre = $cnx->prepare($sql);

        foreach($detalle as $k=>$v){
            $parametros = array(
                ':idventa'		=>$idventa,
                ':item'			=>$v['item'],
                ':idproducto'	=>$v['codigo'],
                ':cantidad'		=>$v['cantidad'],
                ':valor_unitario'=>$v['valor_unitario'],
                ':precio_unitario'=>$v['precio_unitario'],
                ':igv'			=>$v['igv'],
                ':porcentaje_igv'=>$v['porcentaje_igv'],
                ':valor_total'	=> $v['valor_total'],
                ':importe_total'=> $v['importe_total'],
                ':detalle_producto'=> $v['detalle_producto']
                );
            $pre->execute($parametros);
        }
    }

    function obtenerUltimoComprobanteId(){
        $sql = "SELECT * FROM venta ORDER BY id DESC LIMIT 1";
        global $cnx;
        return $cnx->query($sql);
    }

    function actualiza_envio_fe($id, $estado_envio){
        $sql = "UPDATE venta SET feestado = :feestado, fecodigoerror = :fecodigoerror, femensajesunat = :femensajesunat, cdrbase64 = :cdrbase64 WHERE id = :id";
        //var_dump($estado_envio['output']);
        $parametros = array(
            ':feestado'         => $estado_envio['estado'],
            ':fecodigoerror'    => $estado_envio['mensaje_error'],
            ':femensajesunat'   => $estado_envio['descripcion'],
            ':cdrbase64'        => $estado_envio['output'],
            ':id'               => $id
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }


    function listarComprobante(){
		$sql = "SELECT * FROM venta";
		global $cnx;
		return $cnx->query($sql);		
	}

	function listarComprobantePorTipo($tipo_comp){
		$sql = "SELECT * FROM venta WHERE tipocomp=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($tipo_comp));
		return $pre;		
	}


	function obtenerComprobanteId($id){
		$sql = "SELECT * FROM venta WHERE id=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($id));
		return $pre;		
		
	}

	function listarDetalleComprobanteId($id){
		$sql = "SELECT t1.item,t1.cantidad,t2.codigo, t2.lote, t2.nombre, t1.valor_unitario, t1.valor_total,t1.detalle_producto  FROM detalle t1 INNER JOIN producto t2 ON t1.idproducto=t2.codigo  WHERE idventa=?";
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute(array($id));
		return $pre;		
		
	}
	
	function MostrarBoleta($boleta){
		$sql = "SELECT * FROM `venta` where venta.idserie=:serie && venta.correlativo = :correlativo;";
		global $cnx;
		$parametros = array(':serie'=>$boleta['serie'],':correlativo'=>$boleta['correlativo']);
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;	
	}
	
	function actualizarVenta($comprobante){
        $sql = "UPDATE venta SET nota_credito = :descripcion WHERE serie = :serie && correlativo = :correlativo ";
        //var_dump($estado_envio['output']);
        $parametros = array(
            ':serie'         => $comprobante['serie_ref'],
            ':correlativo'    => $comprobante['correlativo_ref'],
            ':descripcion'    => $comprobante['descripcion']
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

}

?>