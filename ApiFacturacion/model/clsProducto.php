<?php

require_once('conexion.php');

class clsProducto{

    public function insertarProducto($producto)
    {
        $sql = "INSERT INTO producto(codigo, nombre, cuenta_contable,precio, tipo_precio, codigoafectacion,unidad,lote,stock) VALUES(NULL, :nombre, :cuenta_contable, :precio, :tipo_precio, :codigoafectacion,:unidad,:lote,:stock)";

        $parametros = array(
           
           
             ':nombre' =>$producto['nombre'],
             ':cuenta_contable' =>$producto['cuenta_contable'],
             ':tipo_precio'=>$producto['tipo_precio'],
             ':precio'=>$producto['precio'],
             ':codigoafectacion'=>$producto['codigo_afectacion'],
             ':unidad'=>$producto['unidad'],
            ':lote'=>$producto['lote'],
            ':stock'=>$producto['stock'],
            
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    
    // UPDATE DE FOLIOS : 
    
    public function actualizarFolio($producto2)
    {
        $sql = "UPDATE producto SET lote =:lote,stock =:stock WHERE codigo=:codigo";

        $parametros = array(
            
            ':codigo' =>$producto2['codigofolio'],
            ':lote'=>$producto2['lotefolio'],
            ':stock'=>$producto2['descfolio']
            
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    public function consultarProductoPorCodigo($codigo)
    {
        $sql = "SELECT * FROM producto WHERE codigo = :codigo";

        $parametros = array(
            ':codigo'          =>  $codigo,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
       
        return $pre;
    }
    
    public function consultarListaProducto()
    {
        $sql = "SELECT * FROM producto order by codigo asc";
        global $cnx;
        return $cnx->query($sql);
    }
    
    
     public function eliminarProducto($codigo)
    {
        $sql = "DELETE FROM producto WHERE codigo = :codigo";

        $parametros = array(
            ':codigo'          =>  $codigo,
        );

        global $cnx;

        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }
    
    public function actualizarproducto($producto){
        
        $sql = "UPDATE producto SET nombre = :nombre, precio = :precio, tipo_precio=:tipo_precio, codigoafectacion=:codigoafectacion,unidad=:unidad, lote=:lote, stock = :stock WHERE codigo = :codigo";
        //var_dump($estado_envio['output']);
        
           global $cnx;
           
           
             $parametros = array(
             ':codigo' =>$producto['codigo'],
             ':nombre' =>$producto['nombre'],
          
             ':precio'=>$producto['precio'],
             ':tipo_precio'=>$producto['tipo_precio'],
             ':codigoafectacion'=>$producto['codigoafectacion'],
             ':unidad'=>$producto['unidad'],
       
            ':lote'=>$producto['lote'],
            ':stock'=>$producto['stock'],
        );
           
            
      
       
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;

    }

}

?>