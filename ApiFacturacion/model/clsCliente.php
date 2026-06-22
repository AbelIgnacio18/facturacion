<?php

require_once('conexion.php');

class clsCliente{



    public function insertarClienteBoleta($cliente)
    {
        $sql = "INSERT INTO cliente(id, tipodoc, nrodoc, razon_social, direccion) VALUES(NULL, :tipodoc, :nrodoc, :razon_social, :direccion)";

        $parametros = array(
            ':tipodoc'          =>  $cliente['tipodoc'],
            ':nrodoc'          =>  $cliente['nrodoc'],
            ':razon_social'          =>  $cliente['razon_social'],
            ':direccion'          =>  $cliente['direccion'],
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }



    public function insertarCliente($cliente)
    {
       
         $sql = "INSERT INTO cliente(id, codigo,razon_social,tipodoc, nrodoc, fecha_pago,fecha_vigencia,fechacolegiatura, direccion,correo_electronico,telefono,Status) 
                            VALUES(NULL, :codigo,:razon_social,:tipodoc,:nrodoc,:fecha_pago,:fecha_vigencia,:fechacolegiatura,:direccion,:correo_electronico,:telefono,:status)";

        
          $parametros = array(
           
           
                    ':codigo'           =>  $cliente['codigo'],//1 DNI 6 es RUC
                    ':razon_social'  =>  $cliente['razon_social'],
                    ':tipodoc'      =>  $cliente['tipodoc'],
                    ':nrodoc'      =>  $cliente['nrodoc'],
                    ':fecha_pago'=>  $cliente['fecha_pago'],
                    ':fecha_vigencia'=>  $cliente['fecha_vigencia'],
                    ':fechacolegiatura'        =>  $cliente['fechacolegiatura'],
                    ':direccion'        =>  $cliente['direccion'],
                    ':correo_electronico'        =>  $cliente['correo_electronico'],
                    ':telefono'        =>  $cliente['telefono'],
                    ':status'        =>  $cliente['status']
            
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }


    public function actualizarStatus($cliente2)
    {
        $sql = "UPDATE cliente SET fecha_pago =:pago,fecha_vigencia =:vigencia,Status=:status WHERE id=:id";

        $parametros = array(
            
            ':id' =>$cliente2['idcliente'],
            ':pago'=>$cliente2['fechapago'],
            ':vigencia'=>$cliente2['fechavigencia'],
            ':status'=>$cliente2['status']
            
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    public function consultarCliente($nrodoc)
    {
        $sql = "SELECT * FROM cliente WHERE nrodoc = :nrodoc";

        $parametros = array(
            ':nrodoc'          =>  $nrodoc,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    public function consultarColegiado($codigo)
    {
        $sql = "SELECT * FROM cliente WHERE codigo = :codigo";

        $parametros = array(
            ':codigo'          =>  $codigo,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    public function consultarClientePorCodigo($codigo)
    {
        $sql = "SELECT * FROM cliente WHERE id = :codigo";

        $parametros = array(
            ':codigo'          =>  $codigo,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    public function consultarListaCliente($status)
    {
        $sql = "SELECT * FROM cliente WHERE Status=:status order by id ASC";
		
		$parametros = array(':status'=>$status);
		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
    }
    
    
    
    
     public function consultarClientePorId($id)
    {
        $sql = "SELECT * FROM cliente WHERE id = :id";

        $parametros = array(
            ':id'          =>  $id,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
       
        return $pre;
    }
    
     public function actualizarCliente($cliente){
$sql = "UPDATE cliente SET codigo =:codigo, razon_social =:razon_social, tipodoc=:tipodoc,nrodoc=:nrodoc, fecha_pago=:fecha_pago,fechacolegiatura=:fechacolegiatura, direccion=:direccion, correo_electronico =:correo_electronico, telefono=:telefono WHERE id =:id";
        //var_dump($estado_envio['output']);
        
    
         
             $parametros = array(
                    ':id'=>  $cliente['id'],
                    ':codigo'=>  $cliente['codigo'],//1 DNI 6 es RUC
                    ':razon_social'=>  $cliente['razon_social'],
                    ':tipodoc'=>  $cliente['tipodoc'],
                    ':nrodoc'=>  $cliente['nrodoc'],
                    ':fecha_pago'=>  $cliente['fecha_pago'],
                    ':fechacolegiatura'=>  $cliente['fechacolegiatura'],
                    ':direccion'=>  $cliente['direccion'],
                    ':correo_electronico'=>  $cliente['correo_electronico'],
                    ':telefono'=>  $cliente['telefono'],
                  
            
        );
        global $cnx;
        $pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;

    }
    
    
      public function eliminarCliente($id)
    {
        $sql = "DELETE FROM cliente WHERE id = :id";

        $parametros = array(
            ':id'          =>  $id,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    
    
    
    
    

}

?>