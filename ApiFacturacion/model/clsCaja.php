<?php

require_once('conexion.php');

class clsCaja{
    
 
     
    

    public function insertarCaja($caja)
    {
        $sql = "INSERT INTO cajaps(id, referencia, usuario, fechaapertura, fechacierre,inicial,gasto,efectivo,transferencia,estado) VALUES(NULL, :referencia, :usuario, :fechaapertura, :fechacierre,:inicial,:gasto,:efectivo,:transferencia,:estado)";

        $parametros = array(
           
           
             ':referencia' =>$caja['referencia'],
             ':usuario' =>$caja['usuario'],
             ':fechaapertura'=>$caja['fechaapertura'],
             ':fechacierre'=>$caja['fechacierre'],
             ':inicial'=>$caja['fondoinicial'],
            ':gasto'=>$caja['gasto'],
            ':efectivo'=>$caja['efectivo'],
             ':transferencia'=>$caja['transferencia'],
             ':estado'=>$caja['estado'],
            
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    

    
    function consultarCajaID($id){
        $sql = "SELECT id, usuario, fechaapertura, fechacierre, inicial, cierre, gasto, efectivo, transferencia FROM cajaps WHERE id=?";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute([$id]);
        return $pre;
    }
    
    function consultarAperturaCaja($fecha,$usuario){
        $sql = "SELECT id, referencia, usuario, fechaapertura, fechacierre, inicial, cierre, gasto, efectivo, transferencia FROM cajaps WHERE DATE(`fechaapertura`) = :fecha && referencia = :usuario" ;
        global $cnx;
        $parametros = array(':fecha' => $fecha,':usuario' => $usuario);
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    
    
    function consultarReporteCaja($id,$usuario){
        
		global $cnx;
        $stmt = $cnx->prepare("SELECT fechaapertura FROM cajaps WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $stmt2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $fechacaja = (new DateTime($stmt2['fechaapertura']))->format('Y-m-d');
        
        $sql = "SELECT v.serie,v.correlativo, v.fecha_emision,v.tipo_operacion,cli.codigo, cli.razon_social, v.total, v.descripcion_detalle  FROM venta v INNER JOIN cliente cli ON cli.id=v.codcliente WHERE v.fecha_emision = :fecha && v.usuario = :usuario order by v.correlativo ASC ";
		
		$pre = $cnx->prepare($sql);
		$pre->execute(array(':fecha' => $fechacaja,':usuario' => $usuario));
		return $pre;		
		
	}

    
    public function consultarListaCaja($user)
    {   $sql = "SELECT *,DATE(fechaapertura) AS fecha_apertura FROM cajaps WHERE referencia=:usuario order by id desc";
       	$parametros = array(
		   
		    ':usuario'=>$user,
		    );
        
        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
        
       
    }
    
    public function obtenerTotales($venta){
      
      
     
		$sql = "SELECT sum(total) as total FROM venta WHERE fecha_emision=:fecha_emision && tipo_operacion=:tipo_operacion  && usuario=:users";
		global $cnx;
		$parametros = array(
		    ':fecha_emision'=>$venta['fecha_emision'],
		    ':tipo_operacion'=>$venta['tipo_operacion'],
		    ':users'=>$venta['users'],
		    );
		    
	
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
	
	
	 public function obteneraperturaandinicial($user){
	   
	     
     
        
		$sql = "SELECT * FROM cajaps WHERE referencia=:usuario AND estado='Aperturado'";
	
		$parametros = array(
		 
		     ':usuario'=>$user,
		    );
		    
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}
    
     public function eliminarProducto($codigo)
    {
        $sql = "DELETE * FROM producto WHERE codigo = :codigo";

        $parametros = array(
            ':codigo'          =>  $codigo,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    public function actualizarcaja($cajacierre){
        
        $sql = "UPDATE cajaps SET fechacierre = :fechacierre,efectivo = :efectivo, transferencia = :transferencia, gasto=:gasto ,estado=:estado WHERE referencia=:referencia and estado='Aperturado'";
        //var_dump($estado_envio['output']);
        
           global $cnx;
           
           
             $parametros = array(
                 
           
                ':referencia' =>$cajacierre['referencia'],
             ':fechacierre' =>$cajacierre['fechacierre'],
             ':efectivo' =>$cajacierre['efectivo'],
             ':transferencia' =>$cajacierre['transferencia'],
             ':gasto'=>$cajacierre['gasto'],
             ':estado'=>$cajacierre['estado'],
          
             
        );
        
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;

    }
    
    public function actualizarCaja2($venta){
        
        $sql = "UPDATE cajaps SET efectivo = :efectivo, transferencia = :transferencia WHERE id = :idCaja";
        
           global $cnx;
           
           
             $parametros = array(
                 
           
             ':idCaja' =>$venta['idCaja'],
             ':efectivo' =>$venta['efectivo'],
             ':transferencia' =>$venta['transferencia']
        );
        
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;

    }

}

?>