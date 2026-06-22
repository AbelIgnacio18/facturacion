<?php
    try{
        $manejador = 'mysql';
        $servidor = 'localhost';
        $base = 'u850070988_bdfacturacion';
        $usuario = 'u850070988_admin';
        $pass = '?jQDa~0U';

        $cadena = "$manejador:host=$servidor;dbname=$base";

        $cnx = new PDO($cadena , $usuario , $pass , array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
        
    }catch(\Throwable $th){
        throw $th;
    }

    

    

